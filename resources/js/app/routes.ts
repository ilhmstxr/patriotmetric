import { createBrowserRouter } from "react-router";
import { Layout } from "./components/Layout";
import { DataProfil } from "./components/DataProfil";
import { FormRubrik } from "./components/FormRubrik";
import { HasilPenilaian } from "./components/HasilPenilaian";
import { PanduanPengguna } from "./components/PanduanPengguna";
import { FormDaftarUlang } from "./components/FormDaftarUlang";

export const router = createBrowserRouter([
  {
    path: "/",
    Component: FormDaftarUlang,
  },
  {
    path: "/dashboard",
    Component: Layout,
    children: [
      { index: true, Component: DataProfil },
      { path: "rubrik", Component: FormRubrik },
      { path: "hasil", Component: HasilPenilaian },
      { path: "panduan", Component: PanduanPengguna },
    ],
  },
]);
