import streamlit as st
import pandas as pd
import numpy as np
import plotly.express as px

# Judul Dashboard
st.set_page_config(page_title="Data Quality Dashboard", layout="wide")
st.title(" Dashboard Monitoring Kualitas Data Desa")
st.markdown("Oleh: Iqbal Ramadhani Mukhlis, S.Kom., M.Kom.")

# 1. Simulasi Data
@st.cache_data
def load_data():
    data = {
        'ID_Desa': [101, 102, 103, 104, 105, 101, 106],
        'Nama_Desa': ['Sukamaju', 'Sukamiskin', 'Sukatani', 'Sukaramai', 'Sukajaya', 'Sukamaju', 'Sukabumi'],
        'Jumlah_Penduduk': [1200, 1500, np.nan, 1100, 2500, 1200, 1800],
        'Suhu_Udara': [28, 300, 27, 29, 28, 28, 26],
        'Kategori': ['Urban', 'Rural', 'Rural', 'urban', 'Rural', 'Urban', 'Urban']
        }
    return pd.DataFrame(data)
df_raw = load_data()

# 2. Sidebar untuk Proses Cleaning
st.sidebar.header(" Kontrol Preprocessing")
clean_data = st.sidebar.checkbox("Lakukan Pembersihan Data")

df_display = df_raw.copy()
if clean_data:
    # Proses Cleaning
    df_display = df_display.drop_duplicates()
    df_display['Jumlah_Penduduk'] = df_display['Jumlah_Penduduk'].fillna(df_display['Jumlah_Penduduk'].median())
    df_display.loc[df_display['Suhu_Udara'] > 50, 'Suhu_Udara'] = df_display[df_display['Suhu_Udara']< 50]['Suhu_Udara'].mean()
    df_display['Kategori'] = df_display['Kategori'].str.capitalize()
st.sidebar.success("Data Berhasil Dibersihkan!")

# 3. Layout Dashboard
col1, col2 = st.columns(2)
with col1:
    st.subheader(" Dataset Viewer")
    st.write(df_display)

with col2:
    st.subheader(" Statistik Ringkas")
    st.write(df_display.describe())
st.divider()

# 4. Visualisasi Kualitas Data
col3, col4 = st.columns(2)

with col3:
    st.subheader("Missing Values Check")
    null_counts = df_display.isnull().sum()
    fig_null = px.bar(x=null_counts.index, y=null_counts.values, labels={'x':'Kolom', 'y':'Jumlah Kosong'})
    st.plotly_chart(fig_null, use_container_width=True)

with col4:
    st.subheader("Distribusi Suhu (Cek Outlier)")
    fig_hist = px.box(df_display, y="Suhu_Udara", points="all")
    st.plotly_chart(fig_hist, use_container_width=True)

st.info("Gunakan checkbox di sidebar untuk melihat perbedaan sebelum dan sesudah data dibersihkan.")