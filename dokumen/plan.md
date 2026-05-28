# Requirements Document

## Introduction

Fitur CMS Compro Management memungkinkan admin untuk mengelola konten halaman company profile (compro) Patriot Metric melalui panel admin Filament, tanpa perlu mengubah kode secara langsung. Fitur ini mencakup pengelolaan konten teks, gambar, daftar item (tim, penghargaan, FAQ, timeline, dll), serta preview halaman compro tanpa navbar langsung dari admin panel.

## Glossary

- **CMS**: Content Management System — sistem untuk mengelola konten website tanpa mengubah kode
- **Admin_Panel**: Panel administrasi berbasis Filament yang digunakan untuk mengelola data aplikasi
- **Compro_Page**: Halaman company profile publik yang dapat diakses pengunjung (welcome, profile, visi-misi, tim, penghargaan, panduan)
- **Content_Block**: Unit konten yang dapat diedit melalui CMS, terdiri dari key unik, tipe konten, dan value
- **Page_Section**: Bagian dari halaman compro yang berisi satu atau lebih Content_Block (misal: hero section, about section)
- **Preview_Mode**: Mode tampilan halaman compro tanpa navbar/footer yang di-render dalam iframe di admin panel untuk keperluan pratinjau langsung saat editing
- **Repeater_Content**: Konten berbentuk daftar/array yang dapat ditambah, diedit, dan dihapus (misal: anggota tim, FAQ, timeline)
- **Static_Content**: Konten teks atau gambar tunggal yang hanya dapat diedit (misal: judul hero, deskripsi about)

## Daftar Konten yang Dapat Dikelola via CMS

### Halaman Welcome (Homepage)
| Section | Konten | Tipe |
|---------|--------|------|
| Hero | Judul utama, deskripsi, teks tombol CTA, link CTA | Static_Content |
| Hero | Background image | Static_Content |
| About | Judul, deskripsi (2 paragraf), bullet points | Static_Content |
| About | URL video YouTube | Static_Content |
| Institusi Partisipan | Daftar institusi (nama, singkatan) | Repeater_Content |
| Timeline | Daftar timeline (nomor, tanggal, judul, deskripsi, artikel terkait) | Repeater_Content |
| Instagram | Daftar post Instagram (URL, gambar, alt text) | Repeater_Content |
| Instagram | Judul section, deskripsi, link akun Instagram | Static_Content |

### Halaman Profile
| Section | Konten | Tipe |
|---------|--------|------|
| Hero | Judul, deskripsi | Static_Content |
| Hero | Background image | Static_Content |
| Latar Belakang | Judul section, paragraf-paragraf deskripsi | Static_Content |
| Tujuan Utama | Judul section, deskripsi section | Static_Content |
| Tujuan Utama | Daftar tujuan (nomor, judul, deskripsi) | Repeater_Content |

### Halaman Visi & Misi
| Section | Konten | Tipe |
|---------|--------|------|
| Hero | Judul, deskripsi | Static_Content |
| Visi | Teks visi | Static_Content |
| Misi | Judul section | Static_Content |
| Misi | Daftar misi (nomor, judul, deskripsi) | Repeater_Content |

### Halaman Tim
| Section | Konten | Tipe |
|---------|--------|------|
| Hero | Judul, deskripsi | Static_Content |
| Team Grid | Daftar anggota tim (nama, role/jabatan, foto) | Repeater_Content |

### Halaman Penghargaan
| Section | Konten | Tipe |
|---------|--------|------|
| Hero | Judul, deskripsi | Static_Content |
| Hero | Background image | Static_Content |
| Daftar Penerima | Judul section | Static_Content |
| Daftar Penerima | Daftar institusi penerima (nama, rating bintang) | Repeater_Content |

### Halaman Panduan
| Section | Konten | Tipe |
|---------|--------|------|
| Hero | Judul, deskripsi | Static_Content |
| Hero | Teks tombol pedoman, link pedoman | Static_Content |
| Steps | Daftar langkah (step label, judul, deskripsi, icon) | Repeater_Content |
| FAQ | Judul section | Static_Content |
| FAQ | Daftar FAQ (pertanyaan, jawaban) | Repeater_Content |

---

## Requirements

### Requirement 1: Manajemen Static Content

**User Story:** As an admin, I want to edit static text and image content on compro pages through the admin panel, so that I can update page content without developer assistance.

#### Acceptance Criteria

1. WHEN an admin navigates to the CMS management page, THE Admin_Panel SHALL display a list of all Compro_Page names with their editable Content_Block items grouped by Page_Section.
2. WHEN an admin selects a Compro_Page to edit, THE Admin_Panel SHALL display a form with all Static_Content fields for that page pre-filled with current values.
3. WHEN an admin submits updated Static_Content values, THE CMS SHALL save the changes to the database, display a success notification, and reflect the updates on the corresponding Compro_Page within 3 seconds of form submission.
4. THE CMS SHALL support the following Static_Content field types: plain text (maximum 255 characters), rich text/HTML (maximum 10,000 characters), image upload (accepted formats: JPG, JPEG, PNG, GIF, and WebP; maximum file size: 2 MB), and URL.
5. IF an admin submits a form with invalid data (empty required field, URL not matching a valid URL pattern, or image file not in JPG/JPEG/PNG/GIF/WebP format or exceeding 2 MB), THEN THE Admin_Panel SHALL display a validation error message indicating the specific field name and the reason for rejection, and SHALL preserve all other entered form values.
6. IF the CMS fails to save changes due to a server or database error, THEN THE Admin_Panel SHALL display an error notification indicating the save failed and SHALL preserve the admin's entered form values so no input is lost.

### Requirement 2: Manajemen Repeater Content

**User Story:** As an admin, I want to add, edit, reorder, and remove list-based content items (team members, FAQ, timeline, etc.), so that I can maintain dynamic collections of content.

#### Acceptance Criteria

1. WHEN an admin opens a Repeater_Content section, THE Admin_Panel SHALL display all existing items in their current display order with options to add, edit, reorder, and delete items.
2. WHEN an admin adds a new Repeater_Content item, THE Admin_Panel SHALL append the item to the list and assign it the next sequential order position, up to a maximum of 50 items per Repeater_Content section.
3. WHEN an admin reorders Repeater_Content items via drag-and-drop, THE CMS SHALL persist the new order and display items in the updated order on the Compro_Page within the same page load.
4. WHEN an admin deletes a Repeater_Content item, THE Admin_Panel SHALL prompt for confirmation before removing the item from the database.
5. THE CMS SHALL support the following Repeater_Content types with required fields and constraints: team members (nama: max 100 characters required, role: max 100 characters required, foto: image upload max 2MB accepting jpg/png/webp), FAQ items (pertanyaan: max 255 characters required, jawaban: max 1000 characters required), timeline items (nomor: max 10 characters required, tanggal: max 50 characters required, judul: max 150 characters required, deskripsi: max 500 characters required), steps (label: max 50 characters required, judul: max 150 characters required, deskripsi: max 500 characters required, icon: max 50 characters required), institutions (nama: max 150 characters required, singkatan: max 20 characters required), award winners (nama: max 150 characters required, rating: numeric value from 1 to 5 in 0.5 increments required).
6. IF an admin submits a Repeater_Content item with any required field empty or exceeding its maximum length, THEN THE Admin_Panel SHALL display a validation error message indicating which field failed and SHALL NOT save the item.
7. IF a Repeater_Content section contains zero items, THEN THE Compro_Page SHALL hide the entire corresponding section instead of displaying an empty container.
8. IF an admin attempts to add an item beyond the maximum of 50 items per section, THEN THE Admin_Panel SHALL display an error message indicating the maximum limit has been reached and SHALL NOT add the item.

### Requirement 3: Preview Halaman Compro (Inline di Admin Panel)

**User Story:** As an admin, I want to see a live preview of compro pages directly within the admin panel alongside the edit form, so that I can immediately verify how content changes will appear to users without leaving the CMS page.

#### Acceptance Criteria

1. WHEN an admin is editing a Compro_Page in the Admin_Panel, THE Admin_Panel SHALL display an inline preview panel (iframe or rendered HTML) alongside or below the edit form showing the selected page without navbar and footer.
2. WHEN an admin saves changes to a Content_Block, THE inline preview SHALL automatically refresh to reflect the updated content within 2 seconds of successful save, without requiring a full page reload.
3. THE inline preview SHALL render the Compro_Page content exactly as it would appear to public visitors, including styling, layout, images, and responsive behavior, but without the navigation bar and footer components.
4. THE Admin_Panel SHALL provide a toggle button or tab to show/hide the preview panel, allowing admins to maximize the edit form area when the preview is not needed.
5. THE inline preview SHALL be rendered within an iframe that loads the Compro_Page in Preview_Mode, accessible only within the authenticated admin session (same session cookie) and not via a publicly accessible URL.
6. WHEN an admin switches between Compro_Page tabs, THE preview panel SHALL update to display the corresponding page content.
7. IF the preview fails to load due to a rendering error, THEN THE Admin_Panel SHALL display a fallback message indicating the preview is temporarily unavailable and provide a manual refresh button.

### Requirement 4: Organisasi Konten per Halaman

**User Story:** As an admin, I want CMS content organized by page and section, so that I can quickly find and edit specific content without confusion.

#### Acceptance Criteria

1. THE Admin_Panel SHALL organize CMS content into separate tabs or navigation items for each Compro_Page (Welcome, Profile, Visi-Misi, Tim, Penghargaan, Panduan).
2. WHILE an admin is viewing a specific Compro_Page tab, THE Admin_Panel SHALL group Content_Block items by their Page_Section and display each group under a visible section heading that shows the Page_Section name.
3. WHEN an admin enters at least 2 characters into the search field, THE Admin_Panel SHALL filter and display Content_Block items whose key name or content value contains the search term (case-insensitive partial match) across all pages within 1 second.
4. IF a search query returns no matching Content_Block items, THEN THE Admin_Panel SHALL display a message indicating no results were found.
5. THE Admin_Panel SHALL display the last modified timestamp for each Content_Block in relative format (e.g., "2 jam yang lalu") with the absolute date-time shown on hover.
6. WHEN an admin selects a Compro_Page tab, THE Admin_Panel SHALL display the content for that page within 2 seconds.

### Requirement 5: Pengelolaan Media/Gambar

**User Story:** As an admin, I want to upload and manage images used on compro pages, so that I can update visual content like hero backgrounds, team photos, and Instagram post thumbnails.

#### Acceptance Criteria

1. WHEN an admin uploads an image through the CMS, THE CMS SHALL convert the image to WebP format, store the converted file in the configured public storage disk, and save the resulting file path in the database.
2. THE CMS SHALL accept image uploads in the following formats: JPEG, PNG, and WebP, validated by MIME type inspection.
3. THE CMS SHALL enforce a maximum file size of 2MB per image upload and a maximum image dimension of 1920x1080 pixels, resizing images that exceed these dimensions while preserving aspect ratio.
4. WHEN an admin replaces an existing image, THE CMS SHALL delete the previous image file from storage to prevent orphaned files.
5. IF an admin uploads an image exceeding the maximum file size or in an unsupported format, THEN THE Admin_Panel SHALL reject the upload and display a validation error message indicating the specific reason for rejection (file too large or unsupported format) without sending the file to the server.
6. IF image processing or storage fails during upload, THEN THE CMS SHALL display an error message indicating the failure, retain the previous image if one existed, and not save an incomplete database record.

### Requirement 6: Seeding Data Awal dari Konten Hardcoded

**User Story:** As an admin, I want the CMS pre-populated with existing hardcoded content from blade templates, so that the transition to CMS-managed content is seamless without data loss.

#### Acceptance Criteria

1. WHEN the CMS seeder is executed, THE CMS SHALL create Content_Block records for all 6 Compro_Pages (Welcome, Profile, Visi-Misi, Tim, Penghargaan, Panduan) covering both Static_Content and Repeater_Content items as defined in the "Daftar Konten yang Dapat Dikelola via CMS" specification.
2. THE seeder SHALL preserve the exact text values, display order of Repeater_Content items, and Page_Section grouping of existing hardcoded content without modification.
3. WHEN seeded content is displayed on a Compro_Page, THE CMS SHALL render the same text content, the same number of list items in the same order, and the same image references as the previous hardcoded blade template version.
4. IF the seeder is run on a database that already contains Content_Block records with matching keys, THEN THE seeder SHALL skip those existing records without overwriting their current values.
5. IF the seeder fails during execution, THEN THE CMS SHALL roll back all database changes from that seeder run so that no partial data remains.

### Requirement 7: Integrasi dengan Resource PengaturanCms yang Ada

**User Story:** As an admin, I want the new CMS compro management to coexist with the existing PengaturanCms resource, so that application settings and compro content are managed in separate, clear sections.

#### Acceptance Criteria

1. THE Admin_Panel SHALL provide a navigation menu item labeled "CMS Compro" in a separate navigation group from the existing "Pengaturan CMS" resource, each with a distinct icon.
2. THE CMS Compro resource SHALL use a dedicated database table separate from the existing `pengaturan_cms` key-value store, with no shared rows or foreign key dependencies between the two tables.
3. WHEN an admin accesses the CMS Compro section, THE Admin_Panel SHALL display only compro page content management (hero sections, about, services, team, awards, guides) without showing application-level settings such as `active_period` or `is_peserta_edit_enabled`.
4. THE existing PengaturanCms resource SHALL remain accessible at its current route, retaining all existing CRUD operations and displaying the same key-value settings data without any schema or behavioral changes.
5. WHEN an admin creates, updates, or deletes a CMS Compro record, THE Admin_Panel SHALL NOT modify, remove, or overwrite any record in the `pengaturan_cms` table.
