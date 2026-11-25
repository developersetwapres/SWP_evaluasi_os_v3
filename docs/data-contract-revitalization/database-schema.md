# Verified Database Schema Map

## Verification status

Runtime database verification was available.

- Laravel reports MySQL `8.0.46`.
- Configured database: `SWP_evaluasi_os_v3`.
- `migrate:status` reports all 19 project migrations as ran.
- Read-only `Schema::getColumnListing('users')` confirms the live cumulative `users` columns.
- No migrations or record changes were executed.

## Migration history inspected

In chronological order:

1. `0001_00_00_000001_create_sikluses_table.php`
2. `0001_00_00_000002_create_bobot_skors_table.php`
3. `0001_00_00_000003_create_kelompok_jabatans_table.php`
4. `0001_00_01_000004_create_jabatans_table.php`
5. `0001_01_01_000000_create_users_table.php`
6. `0001_01_01_000001_create_cache_table.php`
7. `0001_01_01_000002_create_jobs_table.php`
8. `2024_01_01_000000_create_passkeys_table.php`
9. `2025_08_14_170933_add_two_factor_columns_to_users_table.php`
10. `2026_06_29_080560_create_pilars_table.php`
11. `2026_06_29_080709_create_indikators_table.php`
12. `2026_06_29_080911_create_behaviorals_table.php`
13. `2026_06_29_080928_create_outsourcings_table.php`
14. `2026_06_29_080929_create_master_pegawais_table.php`
15. `2026_06_29_080930_create_penugasans_table.php`
16. `2026_06_29_080931_create_penilaians_table.php`
17. `2026_06_29_081629_create_biros_table.php`
18. `2026_07_02_040538_add_development_and_strength_notes_to_penugasans_table.php`
19. `2026_10_08_095145_create_personal_access_tokens_table.php`

## Important cumulative schema

### `users`

Created without a `name` column and never modified by migration to add one.

Actual live columns confirmed:

| Column | Migration/source type | Null/default |
|---|---|---|
| `id` | bigint unsigned primary key | no |
| `userable_id` | string(50) | nullable |
| `userable_type` | string | nullable |
| `nip` | string, unique | nullable |
| `nip_sso` | string | nullable |
| `is_ldap` | boolean | no migration default |
| `email` | string, unique | nullable |
| `role` | JSON | required by migration |
| `password` | string | nullable |
| `two_factor_secret` | text | nullable |
| `two_factor_recovery_codes` | text | nullable |
| `two_factor_confirmed_at` | timestamp | nullable |
| `is_active` | boolean | default true |
| `email_verified_at` | timestamp | nullable |
| `remember_token` | remember token string | nullable |
| `created_at` | timestamp | nullable |
| `updated_at` | timestamp | nullable |

Runtime `Schema::getColumnListing('users')` returned exactly the 17 columns above.

No live or migration evidence establishes `users.name`.

### `master_pegawais`

Display-name source: `name`.

- `id` primary key
- `uuid` unique
- `nip` unique
- `name`
- `image`
- nullable organization fields: `kode_instansi`, `kode_unit`, `kode_deputi`, `kode_biro`, `kode_bagian`, `kode_subbagian`
- nullable `jabatan`
- timestamps
- composite structure index `idx_kode_unitstruktur`

### `outsourcings`

Display-name source: `name`.

- `id` primary key
- `uuid` unique
- `name`
- `image`
- nullable `jabatan_id` foreign key to `jabatans.id`, `nullOnDelete`
- `is_active` boolean default true
- nullable `nip`
- nullable `kode_biro`
- timestamps

### `penugasans`

- `id` primary key
- `uuid` unique
- `bobot_skor_id` foreign key to `bobot_skors.id`, cascading delete/update
- `siklus_id` foreign key to `sikluses.id`, cascading delete/update
- `outsourcing_id` foreign key to `outsourcings.id`, cascading delete/update
- `penilai_id` foreign key to `users.id`, cascading delete/update
- `tipe_penilai` enum: `atasan`, `penerima_layanan1`, `penerima_layanan2`
- `status` enum: `completed`, `draft`, `incomplete`, default `incomplete`
- nullable `catatan`
- nullable `area_pengembangan`
- nullable `kekuatan_teramati`
- timestamps
- unique `siklus_id, outsourcing_id, tipe_penilai`
- index `outsourcing_id`
- index `penilai_id`

### `penilaians`

- `id` primary key
- `uuid` unique
- `indikator_id` foreign key to `indikators.id`, restrict delete, cascade update
- `penugasan_id` foreign key to `penugasans.id`, cascade delete/update
- `nilai` small integer
- timestamps
- unique `penugasan_id, indikator_id`
- index `indikator_id`

### `jabatans`

- `id` primary key
- `kelompok_jabatan_id` foreign key to `kelompok_jabatans.id`
- unique `nama_jabatan`
- unique `kode_jabatan`
- timestamps

### `kelompok_jabatans`

- `id` primary key
- unique `uuid`
- unique `nama_kelompok`
- timestamps

### `bobot_skors`

- `id` primary key
- nullable `siklus_id` foreign key to `sikluses.id`
- `title`
- unique `kode_bobot`
- decimal `bobot(5,2)`
- timestamps

### `sikluses`

Inspect migration file for exact title/date/active fields before relying on transformations.

### `pilars`

- `id` primary key
- unique `title`
- unique `uuid`
- `bobot_skor_id` foreign key to `bobot_skors.id`, cascade delete/update
- timestamps

### `indikators`

- `id` primary key
- `pilar_id` foreign key to `pilars.id`, cascade delete/update
- `kelompok_jabatan_id` foreign key to `kelompok_jabatans.id`, cascade delete/update
- `title`
- `defenisi`
- unique `uuid`
- JSON `example`
- timestamps

There is no `jabatan_id` column on `indikators`.

### `behaviorals`

- `id` primary key
- `indikator_id` foreign key to `indikators.id`, cascade delete/update
- `behavioral`
- unique `uuid`
- `skor`
- timestamps

### `biros`

- `kode_biro` primary key
- nullable `nama_biro`
- nullable `kode_deputi`
- timestamps

## Polymorphic `User` mapping

`User::userable()` uses `morphTo` with:

- type column: `users.userable_type`
- ID column: `users.userable_id`
- owner key: `users.nip`

The related models are:

- `App\Models\MasterPegawai`, matched by NIP and exposing `name`, `jabatan`, `image`, `uuid`, `kode_biro`.
- `App\Models\Outsourcing`, matched by NIP and exposing `name`, `image`, `jabatan_id`, `kode_biro`, `uuid`.

Display name is not stored on `users`. It must be read from the polymorphic `userable` model:

- `MasterPegawai.name`
- `Outsourcing.name`

## Relationships

- `Penugasan.belongsTo(User, penilai_id)`
- `Penugasan.belongsTo(Siklus, siklus_id)`
- `Penugasan.belongsTo(Outsourcing, outsourcing_id)`
- `Penugasan.belongsTo(BobotSkor, bobot_skor_id)`
- `Penugasan.hasMany(Penilaian, penugasan_id)`
- `Outsourcing.belongsTo(Jabatan, jabatan_id)`
- `Outsourcing.belongsTo(Biro, kode_biro, kode_biro)`
- `Outsourcing.hasMany(Penugasan, outsourcing_id)`
- `Outsourcing.morphOne(User, userable, ownerKey=nip)`
- `MasterPegawai.belongsTo(Biro, kode_biro, kode_biro)`
- `MasterPegawai.morphOne(User, userable, ownerKey=nip)`
- `Penilaian.belongsTo(Indikator, indikator_id)`
- `Penilaian.belongsTo(Penugasan, penugasan_id)`
- `Indikator.belongsTo(Pilar, pilar_id)`
- `Indikator.belongsTo(KelompokJabatan, kelompok_jabatan_id)`
- `Indikator.hasMany(Behavioral, indikator_id)`
- `Pilar.belongsTo(BobotSkor, bobot_skor_id)`
- `Pilar.hasMany(Indikator, pilar_id)`

## Original SQL regression

Failing eager-load selection:

```sql
SELECT id, name, email, userable_id, userable_type, nip
FROM users
WHERE users.id IN (...)
```

The `name` column came from modified Phase 2 `users` eager-load `select()` lists. The surrounding Penilaian rekap mapping was not the source.

The exact corrected selections are now:

```php
select(['id', 'email', 'userable_id', 'userable_type', 'nip'])
```

Then `evaluators.userable` is loaded with polymorphic constraints selecting `name` only from `MasterPegawai` or `Outsourcing`.

## Regression prevention map

Any future `users` query must not select:

- `name`
- `nip_sso` unless explicitly required
- `is_active` or profile fields without a verified frontend requirement

Display names must come from `master_pegawais.name` or `outsourcings.name` through `userable`.
