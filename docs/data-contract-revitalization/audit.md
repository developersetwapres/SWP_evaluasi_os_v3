# Data Contract Revitalization Audit

Audit scope: Laravel + React/Inertia frontend.
Audit status: PHASE 1 complete; no application source code was modified.

## A. Architecture Overview

The application is a monorepository: Laravel and React/Inertia are both under `src/`.

- Laravel application: `src/app`
- Routes: `src/routes`
- Laravel views/React entry: `src/resources/views/app.blade.php` and `src/resources/js`
- Inertia pages: `src/resources/js/pages`
- Shared React components: `src/resources/js/components`
- Layouts: `src/resources/js/layouts`
- Existing TypeScript types: `src/resources/js/types`
- Services: `src/app/Services`
- Laravel Resources: `src/app/Http/Resources`
- Models: `src/app/Models`
- Migrations: `src/database/migrations`
- Tests: `src/tests`

Backend:
- Laravel 13, PHP 8.3, Inertia Laravel 3.
- Domain areas are evaluator, admin/operator, settings, authentication, synchronization, and Excel export.
- Controllers return named Inertia page components. Several transformations are also performed in services or resources.
- There are no generally used Laravel Resources across the whole application; existing Resources cover assignment/status payloads and one API domain.

Frontend:
- React 19, Inertia React 3, TypeScript 5.7 strict mode, Vite 8.
- `src/resources/js/app.tsx` dynamically resolves pages and applies layouts:
  - `auth/*`: `AuthLayout`
  - `settings/*`: `SettingsLayout`
  - `admin/*`: `AppLayout`
  - `evaluator/*`: no layout configured
  - export pages: no layout configured
- Alias convention: `@/*` -> `src/resources/js/*`.
- Existing types are split into `types/auth.ts`, `types/navigation.ts`, `types/ui.ts`, `types/penugasan.ts`, `types/outsourcing.ts`, and `types/jabatan.ts`.
- Page-local interfaces/types are also used, but many props use `any` or `unknown` with runtime normalization.

## B. Inertia Page Inventory

There are **27 page files**. The following table covers each file and its source.

| Page name | Frontend file | Backend source |
|---|---|---|
| `admin/penugasan/page` | `src/resources/js/pages/admin/penugasan/page.tsx` | `PenugasanController@index`, `PenugasanDashboardService` |
| `admin/rekaphasil/page` | `src/resources/js/pages/admin/rekaphasil/page.tsx` | `PenilaianController@rekaphasil`, `EvaluationEngineService` |
| `admin/ranking/page` | `src/resources/js/pages/admin/ranking/page.tsx` | `PenilaianController@ranking`, `EvaluationEngineService` |
| `admin/statuspenilaian/page` | `src/resources/js/pages/admin/statuspenilaian/page.tsx` | `PenugasanController@statusPenilaian`, `PenugasanDashboardService`, Resources |
| `admin/saranperbaikan/page` | `src/resources/js/pages/admin/saranperbaikan/page.tsx` | `PenugasanController@saranPerbaikan`, `SaranPerbaikanEvaluatorService` |
| `admin/user/page` | `src/resources/js/pages/admin/user/page.tsx` | `UserController@index` |
| `admin/detail/nilai-akhir` | `src/resources/js/pages/admin/detail/nilai-akhir.tsx` | `OutsourcingController@nilaiAkhir`, `EvaluationEngineService` |
| `admin/detail/rekap-nilai` | `src/resources/js/pages/admin/detail/rekap-nilai.tsx` | `OutsourcingController@rekapNilai`, `EvaluationEngineService` |
| `admin/detail/catatan-evaluator` | `src/resources/js/pages/admin/detail/catatan-evaluator.tsx` | `OutsourcingController@catatanEvaluator` |
| `admin/detail/nilai-perkriteria` | `src/resources/js/pages/admin/detail/nilai-perkriteria.tsx` | `OutsourcingController@nilaiPerkriteria`, `EvaluationEngineService` |
| `evaluator/page` | `src/resources/js/pages/evaluator/page.tsx` | `PenugasanController@home` (also a legacy `homex` method) |
| `evaluator/evaluation-form` | `src/resources/js/pages/evaluator/evaluation-form.tsx` | `PenilaianController@create` |
| `evaluator/viewscore` | `src/resources/js/pages/evaluator/viewscore.tsx` | `PenilaianController@create` when assignment status is completed |
| `exportexcel/exportToExcelMentahan` | `src/resources/js/pages/exportexcel/exportToExcelMentahan.tsx` | `OutsourcingController@exportNilaiMentahan`, `EvaluationEngineService` |
| `exportexcel/exportSaranPerbaikan` | `src/resources/js/pages/exportexcel/exportSaranPerbaikan.tsx` | `PenugasanController@exportSaranPerbaikan` |
| `exportexcel/ETXpenilaianByOutsourcing` | `src/resources/js/pages/exportexcel/ETXpenilaianByOutsourcing.tsx` | `PenugasanController@byOutsourcings`, status Resource |
| `exportexcel/ETXpenilaianByEvaluator` | `src/resources/js/pages/exportexcel/ETXpenilaianByEvaluator.tsx` | `PenugasanController@byEvaluators`, status Resource |
| `settings/profile` | `src/resources/js/pages/settings/profile.tsx` | `Settings/ProfileController@edit` |
| `settings/security` | `src/resources/js/pages/settings/security.tsx` | `Settings/SecurityController@edit` |
| `settings/appearance` | `src/resources/js/pages/settings/appearance.tsx` | `Route::inertia`, no controller |
| `auth/login` | `src/resources/js/pages/auth/login.tsx` | `FortifyServiceProvider::configureViews` |
| `auth/reset-password` | `src/resources/js/pages/auth/reset-password.tsx` | `FortifyServiceProvider::configureViews` |
| `auth/forgot-password` | `src/resources/js/pages/auth/forgot-password.tsx` | `FortifyServiceProvider::configureViews` |
| `auth/verify-email` | `src/resources/js/pages/auth/verify-email.tsx` | `FortifyServiceProvider::configureViews` |
| `auth/two-factor-challenge` | `src/resources/js/pages/auth/two-factor-challenge.tsx` | `FortifyServiceProvider::configureViews` |
| `auth/confirm-password` | `src/resources/js/pages/auth/confirm-password.tsx` | `FortifyServiceProvider::configureViews` |
| `auth/register` | `src/resources/js/pages/auth/register.tsx` | No active route/source; registration view is commented in Fortify provider |

## C. Page-by-Page Data Contract Audit

### C1. Admin assignment page

- Page: `admin/penugasan/page`
- Props: `outsourcing`, `evaluators`, optional `message`.
- `outsourcing` source: `PenugasanDashboardService::assignmentOutsourcings()`, transformed by `PenugasanAssignmentOutsourcingResource`.
- Required fields:
  - outsourcing: `uuid`, `name`, `image`, `jabatan`/`nama_jabatan`, `biro`, `evaluators.{atasan,penerima_layanan1,penerima_layanan2}.{name,uuid,jabatan}`
- evaluator options: `uuid`, `name`, `image`, `jabatan`, `kode_biro`, `biro.{kode_biro,nama_biro}`.
- Frontend usage: cards, search, assignment progress, dialog selection, POST selected UUIDs.
- Query: active outsourcing only, active cycle matching penugasan, eager loads jabatan/biro and penugasan/evaluator morph.
- Verified concerns:
  - `biro` is eager loaded but only `nama_biro` is used for outsourcing display.
  - `PenugasanAssignmentOutsourcingResource` emits duplicate `jabatan` and `nama_jabatan`; only `nama_jabatan` and `biro` are used by the page. `jabatan` is currently redundant.
- Typing: `evaluators` and `items` are `any[]`; the existing `PenugasanPeer` type omits explicit nullable shapes and broadens fields inconsistently.
- Proposed contract: one assignment card type plus one evaluator option type. Keep only fields consumed by assignment and submission.

### C2. Rekap hasil

- Props: `evaluationResults`, `filters.search`, `filters.kodeBiro`, `units`.
- Source: `PenilaianController@rekaphasil`, `EvaluationEngineService::calculate`.
- Required fields:
  - result: `id`, `nip`, `name`, `uuid`, `image`, `biro`, `jabatan`, `status`, `finalTotalScore`, `evaluatorScores[]`
  - evaluator: `type`, `averageScore`, `bobot`, `weightedScore` (visible fields also include status/name/notes for wider engine output, but this page only traces a subset).
- Unit: `kodeBiro`, `namaBiro`.
- Interaction: search and unit partial reload; export by row/status; detail routes use `uuid`.
- Backend query: Outsourcing active rows with full nested eager loads for penugasan, bobot, evaluator, penilaian, indicator, pilar, bobot pilar, biro, and jabatan. It then calculates and discards many raw fields.
- Verified concerns:
  - `nip` is mapped into `evaluationResults` but not used by the page.
  - Jabatan/biro relationship objects are transformed to names; they should not be exposed as full objects.
  - `withCount`/aggregations are absent; calculation requires row-level data but query columns are unrestricted.
- No pagination: all matching active outsourcing rows are loaded.

### C3. Ranking

- Props: `outsourcingData`, `allJabatan`, `selectedJabatanId`.
- Source: `PenilaianController@ranking`, `EvaluationEngineService::calculateRankingByJabatan`.
- Required fields: groups `{jabatan, ranking[]}`; ranking rows `{nama,atasan,penerima_layanan,teman,total,ranking}`.
- `allJabatan`: `id`, `nama_jabatan`.
- Frontend usage: chart/table/filter/pagination. The fallback slug logic is unused in normal backend structure.
- Query concern: Outsourcing, jabatan, and active penugasan relations are loaded without selective column lists. Penilaian/indicator/pilar/bobot chains are needed for calculation, but current query loads full models.
- No server pagination: frontend slices 20 client-side.

### C4. Status penilaian

- Props: `byOutsourcings`, `byEvaluators`.
- Sources: `PenugasanDashboardService::statusByOutsourcings/statusByEvaluators`, existing status Resources.
- By-outsourcing fields: `outsourcing_name/image/jabatan`, and three evaluator slots `{name,image,uuid,status}`.
- By-evaluator fields: `outsourcing_name/image/jabatan`, `tipe_penilai`, `status`, `evaluator_name/image/uuid`.
- Frontend uses names, positions, statuses and export links. Images/UUIDs are Resource fields but are not used by this page.
- Verified concern: `statusByOutsourcings` loads every penugasan because it has no active-siklus constraint, while `statusByEvaluators` filters assignments by active cycle.
- No pagination.
- Export pages consume subsets: by-outsourcing only name/jabatan/evaluator names/statuses; by-evaluator only evaluator/name/type/status/outsourcing/jabatan.

### C5. Saran/perbaikan

- Props: `Outsourcings`, `allJabatan`, `selectedJabatanId`.
- Source: `SaranPerbaikanEvaluatorService::saran`, `Jabatan` options.
- Required structure:
  - groups `{jabatan,jabatan_id,evaluators[]}`
  - employee `{name,image,penugasan[]}`
  - penugasan `{nama,image,tipe_penilai,catatan,area_pengembangan,kekuatan_teramati}`
- Frontend derives counts and normalized feedback.
- Query loads all active outsourcing and all penugasan/evaluator morphable data. It does not select needed columns or constrain returned feedback fields.
- Relationship keys are retained implicitly. Selective loading would need outsourcing `id,jabatan_id,name,image`, penugasan `outsourcing_id,tipe_penilai,catatan,area_pengembangan,kekuatan_teramati,penilai_id`, and evaluator morph keys.
- Export variant uses the same service without a job-position filter and only consumes employee and feedback fields.

### C6. User management

- Props: `initialUsers`, `totalOutsourcing`, `outsourcingAktif`, `outsourcingNonAktif`, `totalPegawai`, `pegawaiMenilai`, `pegawaiTidakMenilai`, `jabatans`, `biros`.
- Source: `UserController@index`.
- Outsourcing row fields: `id,name,image,nip,email,role,is_active,type,biro,kode_biro,jabatan,id_jabatan,jumlahDinilai`.
- Evaluator row fields: `id,name,image,email,type,nip,biro,kode_biro,role,jabatan,id_jabatan,jumlahDinilai`.
- Required for create/edit/actions: name, nip/email, jabatan id, bureau code, active status, image, password for create/update.
- Queries:
  - hard-coded `siklus_id=1` in two withCount/count/whereHas branches.
  - unrestricted `Outsourcing`, `MasterPegawai`, `Jabatan`, and `Biro` retrieval.
  - evaluator page maps `jabatan` and `id_jabatan` from scalar `MasterPegawai.jabatan`, not a Jabatan relation.
  - outsourcing morphOne user includes account fields needed by form; full user model is not needed.
- `jabatans` and `biros` are loaded even for invalid/unsupported `$user` routes and in both tabs.
- Counts are always calculated even though only one user tab is relevant.

### C7. Final score detail

- Props: `name`, `uuid`, `image`, `jabatan`, `unit_kerja`, `status`, `finalTotalScore`, `pilarScores`, `evaluatorScores`.
- Source: `OutsourcingController@nilaiAkhir`, `EvaluationEngineService::calculate`.
- Required fields:
  - header: name, uuid, image, jabatan name, bureau name, status, final score.
  - pilar: id, title, averageScore, weightedScore, bobot.
  - evaluator: `evaluatorName,type,averageScore,weightedScore,bobot,status,uuidPenugasan`.
- Interaction: double-click evaluator badge opens reset assignment dialog; reset uses `uuidPenugasan`.
- Query loads all penugasan and nested relation data, plus jabatan/biro. Many raw fields are transformed away.
- Potential concern: `id` is not currently in the page payload; reset uses `uuidPenugasan`.

### C8. Per-pilar detail

- Prop: `peraspek`.
- Source: `calculateDetailPerPilar`.
- Required: `uuid`, `aspects[].{id,aspectTitle,evaluators[].{evaluatorName,evaluatorType,averageScore,weightedScore,bobot},total}`.
- Frontend uses evaluator/bobot list and weighted totals. It also uses `evaluatorType`.
- Backend loads penugasan/evaluator/penilaian/pilar data, then recalculates from the general engine.

### C9. Evaluator notes

- Prop: `penugasans`, `uuidOs`.
- Source: `OutsourcingController@catatanEvaluator`.
- Required: `tipe_penilai`, `catatan`, evaluator userable `{name}`.
- Frontend also handles strengths/development UI but currently only maps `catatan`; no strengths/development fields are used in this component.
- Query loads `outsourcing->penugasan` and evaluator morphable user; no narrow columns.

### C10. Per-criterion detail

- Props: `rekapPerAspek`, `evaluationData`, `uuidOs`.
- Source: `OutsourcingController@nilaiPerkriteria`, `EvaluationEngineService`.
- `rekapPerAspek` is computed summary but `ViewScoreComponent` does not currently declare or consume it, so it is currently passed but effectively unused/UNVERIFIED for child usage.
- `evaluationData` required nested structure:
  - pilar: `id,title,bobot_skor/bobotSkor`
  - indicators: `id,title,defenisi/description,kelompok_jabatan_id`
  - behaviorals: `skor`, `behavioral`
  - existing penilaian: `nilai`
- Conditional state: no penugasan returns `rekapPerAspek=null` and `evaluationData=[]`.
- Query issue: all Piliars/indicators/behaviorals are loaded unrestricted, but filtered by job-family group and selected penugasan.

### C11. Evaluator dashboard

- Props: `semesterHistory`, `penugasanPeer`, `resultScore`, `resultScoreHistory`, `typeUser`, `siklusAktif`.
- Source: `PenugasanController@home`.
- Required by frontend:
  - user header comes from shared `auth.user.userable`, not a page prop.
  - active assignment: id/uuid/status/tipe_penilai, outsourcing uuid/name/image/jabatan.
  - current/ historical personal score: engine evaluator objects.
  - semester history: id/name/period/status/employees[].{id,uuid,outsourcings.name/jabatan/image,status,tipe_penilai,score}.
- `typeUser` controls outsourcing-specific versus employee-specific branches.
- Query concerns:
  - `$myPenugasans` loads full siklus, outsourcing, jabatan, scores, BobotSkor, evaluator morphable, and penilaian chains, but most fields are transformed away.
  - User profile uses `$user->userable`, but if Outsourcing it needs jibatan/biro and if MasterPegawai it needs name/jabatan/biro.
  - `$siklusAktif` is dereferenced with `->id` before any null guard in subsequent history queries.
  - active/current penugasan query and history query both load potentially unnecessary full nested models.
  - `penugasanPeer` uses partial select plus eager loading `siklus` and `outsourcings` without matching relation keys? `outsourcing_id/siklus_id/status/uuid/tipe_penilai` include needed relation columns, but outsourcing eager fields are currently unrestricted.

### C12. Evaluation form

- Props: `outsourcing`, `evaluator`, `uuidPenugasanPeer`, `tipePenilai`, `overallNotes`, `developmentArea`, `observedStrengths`, `evaluationData`.
- Source: `PenilaianController@create`, `EvaluationEngineService::getEvaluationData`.
- Required outsourcing: id (optional but included through full model), name, image, jabatan object, biro object.
- Required evaluator: id, name, jabatan scalar or object, biro object, image.
- Required instrument:
  - pillar id/title and `bobot_skor.bobot`
  - indicator id/title/defenisi
  - behavioral options `{skor,behavioral}`
- Submission fields: `scores[{indicator_id,value}]`, `development_area`, `observed_strengths`, `notes`; hidden IDs are not submitted to backend validation in the current form.
- The page normalizes several legacy/alternative keys and accepts `evaluationData` as unknown.
- Backend concern: full outsourcing/evaluator models are loaded and then delivered; more complete models are not needed. Partial eager loads/Resources are better for this contract.

### C13. View completed score

- Props: `outsourcing`, `evaluator`, `evaluationData`, `overallNotes`, `tipePenilai`, `developmentArea`, `observedStrengths`.
- Source: same `PenilaianController@create` branch.
- Frontend uses header identities plus `evaluationData` and three nullable notes.
- `rekapPerAspek` is in `ViewScoreProps` but is not destructured or passed from this page and has no verified backend use; verify as obsolete.
- Shared child `ViewScoreComponent` consumes pillar/indicator/behavioral/penilaian data.

### C14. Excel export pages

- Mentahan: prop `evaluationResults`, array with group `kelompokJabatan` and `penugasan[]` fields; the frontend builds workbook and returns to history.
- Saran export: prop `outsourcings`, with the same nested feedback structure as Saran page.
- Status export by outsourcing: prop `outsourcings`, flat transformed status Resource; frontend writes Excel and redirects.
- Status export by evaluator: prop `evaluators`, flat transformed status Resource; frontend writes Excel and redirects.

### C15. Settings and auth pages

- Profile: `mustVerifyEmail`, `status`; shared `auth.user` supplies name/email/verified timestamp.
- Security: `passwordRules`, `canManageTwoFactor`, `requiresConfirmation`, `twoFactorEnabled`, `canManagePasskeys`, `passkeys[]`.
- Passkey fields: `id,name,authenticator,created_at_diff,last_used_at_diff`.
- Appearance: no page props; account appearance preference is local/cookie-based.
- Login: `status`, `canResetPassword`; reset-password: `email`,`token`,`passwordRules`; forgot-password: `status`; verify-email: `status`; 2FA challenge: no explicitly declared props; confirm password: no explicitly declared props.
- `auth/register` exists but is not a currently active Fortify page (view registration is commented).

## D. Frontend Data Requirements

The following reusable payload families are evidenced:

1. **Person summary**: `name,image,jabatan,biro`.
2. **Outsourcing card**: UUID, name, image, jabatan name, bureau name.
3. **Evaluator option**: UUID, name, image, job title, bureau code/name.
4. **Assignment slot**: name, UUID, job title, and status inferred from presence.
5. **Penugasan**: UUID/status/type and the outsourcing or evaluator identity.
6. **Evaluation instrument**: pilar/indicator/behavioral/score.
7. **Evaluation result**: evaluator type, status, scores, weight and notes.
8. **Pilar result**: title/average/weight/weighted score.
9. **Feedback**: strengths/development/other note plus reviewer identity/type.
10. **Status rows**: evaluator/outsource identity, position, type, status.
11. **Pagination contract**: no actual backend paginator was found in audited Inertia methods; several arrays are client-paginated or unpaginated.

No prop that is merely passed into a page and never traced past it is considered used; the following currently appear redundant or weakly traced:
- Rekap hasil: `nip`.
- Penugasan assignment: duplicate `jabatan` beside `nama_jabatan`.
- Status Resources: image/UUID fields are not used in the status table but may be used by future export? The status table and export currently do not consume image/UUID in some variants.
- View score: `rekapPerAspek` not consumed by `ViewScoreComponent`.
- Export pages: `PenugasanExport.id` is declared but workbook uses no `id`.
- Penilaian form: `evaluator_id` and `outsourcing_id` exist in form state but are not read by StorePenilaianRequest payload usage (verified by the controller).

UNVERIFIED: exact runtime types of database decimal/enum/date fields, because no schema casts or live database response evidence was audited. Numeric values are expected based on frontend arithmetic and resource output, but should be confirmed after sample/feature instrumentation.

## E. Backend Query Audit

### Verified strong candidates

1. **Unrestricted nested eager loads**:
   - `rekaphasil`: `penugasanAktif.*`, evaluator userable, penilaian, indicator, pilar, bobot.
   - ranking: Outsourcing and active penugasan chains.
   - final score: penugasan, bobot, evaluator, penilaian, indicator, pilar.
   - evaluator dashboard: multiple full chains.
   - per-criterion: full Pilar/Indikator/Behavioral/Penilaian retrieval.
2. **Calculated payloads replace raw models**:
   - Rekap, ranking, final score, per-pilar, raw export, and evaluator dashboard calculate arrays but initially load more than is serialized.
3. **No server-side pagination**:
   - User management, rekap, ranking, status, Saran, assignment, and export routes return full collections/arrays.
4. **Hard-coded cycle**:
   - `UserController@index` uses `siklus_id=1` for counts and assignments. This is behavior-coupled and may not reflect active cycle semantics.
5. **Active status by outsourcing**:
   - `statusByOutsourcings()` loads all penugasan for each active outsourcing. It should filter active cycle if status is page-cycle-specific.
6. **Potential N+1**:
   - Saran service loads nested relations through eager loading, no obvious lazy loop, but the morphable/evaluator data is broad.
   - Catatan loads penugasan, then calls `load('evaluators.userable')`; eager loading on route entry would avoid the extra round trip.
   - `PenilaianController::create` loads outsourcing/evaluator relations after route binding, potentially adding known fixed queries rather than N+1.
7. **No selective column retrieval** in many relationship loads, despite the project already using some selective loads in dashboard and resource queries.

### Not verified as performance defects

- Whether Eloquent is generating duplicate SQL for morph relations.
- Actual SQL plans, row counts, response payload bytes, or DB indexes.
- Whether dashboard datasets are currently small enough that paginating would have no practical need.
- Whether there are missing indexes beyond existing migration indexes. A live DB or query listener is required.

## F. Relationship Audit

Key relationships from actual code:

| Relation | Model/key evidence | Required keys |
|---|---|---|
| Outsourcing -> Jabatan | `belongsTo(Jabatan, 'jabatan_id')` | parent `id`; outsourcing `jabatan_id` |
| Outsourcing -> Biro | `belongsTo(Biro, 'kode_biro', 'kode_biro')` | both `kode_biro` |
| Outsourcing -> Penugasan | `hasMany(Penugasan, 'outsourcing_id')` | outsourcing `id`; penugasan `outsourcing_id` |
| Outsourcing -> User | morphOne with `nip` owner key | outsourcing `nip`; user `userable_id/userable_type` |
| Penugasan -> User evaluator | `belongsTo(User, 'penilai_id')` | penugasan `penilai_id`; user `id` |
| Penugasan -> Siklus | `belongsTo(Siklus,'siklus_id')` | penugasan `siklus_id`; siklus `id` |
| Penugasan -> BobotSkor | `belongsTo(BobotSkor,'bobot_skor_id')` | `bobot_skor_id`, `id`,`bobot` |
| Penugasan -> Penilaian | `hasMany(Penilaian,'penugasan_id')` | penugasan `id`; penilaian `penugasan_id` |
| Penilaian -> Indikator | `belongsTo(Indikator,'indikator_id')` | `indikator_id`,`id`,`nilai` |
| Indikator -> Pilar | `belongsTo(Pilar,'pilar_id')` | `pilar_id`,`id` |
| Pilar -> BobotSkor | `belongsTo(BobotSkor,'bobot_skor_id')` | `bobot_skor_id`,`id`,`bobot` |
| Indikator -> Behavioral | `hasMany(Behavioral,'indikator_id')` | indicator `id`; behavioral `indikator_id`,`skor`,`behavioral` |
| Indikator -> Penilaian | `hasOne(Penilaian)` (bug-prone for penugasan uniqueness) | indicator `id`; penilaian `indikator_id`,`penugasan_id`,`nilai` |
| MasterPegawai -> Biro | string-key relation | `kode_biro` |
| MasterPegawai -> User | morphOne with `nip` owner key | MasterPegawai `nip`; User `userable_id/userable_type` |

The polymorphic evaluator relation requires preserving `userable_type`, `userable_id`, and polymorphic matching keys. For `MasterPegawai`, `name`, `jabatan`, `image`, `uuid`, `biro` are page needs. For `Outsourcing`, `name`, `jabatan_id`, `image`, `uuid`, `biro/kode_biro` are needs.

## G. Shared Props Audit

`HandleInertiaRequests::share()` defines:
- `name`: app name.
- `auth.user`: full request user loaded with polymorphic `userable`.
- `sidebarOpen`: boolean from cookie.
- `flash.pathTemp`, `flash.success`, `flash.error`: lazy closures.

Additional Inertia core shared props are also present by parent middleware.

Structure:
- Auth user model with hidden password fields removed; broad unknown fields remain in TS.
- Outsourcing userable eager loads `biro` and `jabatan`.
- MasterPegawai userable eager loads `biro`.
- No current `flash` entry in global TypeScript `InertiaConfig.sharedPageProps`; index signature `[key:string]:unknown` covers it weakly.
- `sidebarOpen` is declared but usage needs verification by layout/app shell.

Optimization:
- Shared auth user is used by layouts/profile/evaluator and can remain, but avoid loading every polymorphic model column.
- Proposed shared shape: explicit `id,name,email,email_verified_at,role,userable_type,userable:{id?,uuid,name,image,jabatan,biro}`.
- Role/permission behavior appears to be route middleware rather than shared frontend data. Do not remove shared role assumptions until layout usage is fully traced.

## H. Resource Architecture Proposal

Use three Resource categories:

1. **Shared reusable nested Resources**
   - `PersonIdentityResource`: UUID/name/image.
   - `JabatanSummaryResource`: id/nama_jabatan.
   - `BiroSummaryResource`: kode_biro/nama_biro.
   - `EvaluatorSlotResource`: name/uuid/jabatan/status.

2. **Intentional page/service payload Resources**
   - Assignment card: page-specific fields only.
   - Assignment evaluator option: lightweight dropdown fields.
   - Status by outsourcing/by evaluator: separate Resources are justified because payload roots differ.
   - Evaluation form: dedicated page payload for OS/evaluator/instrument, not broad model Resources.
   - Result summary: separate final-score and ranking/rekap payloads.
   - Feedback: dedicated feedback payload.

Existing Resources should be retained for status/assignment as a starting point but aligned to exact page TS contracts. Do not replace all model serialization with universal Resources.

Collections:
- Array/collection-only Resources are sufficient for current no-paginator pages.
- Introduce Laravel paginated resource only when server pagination is implemented.
- `Resource::collection(...)->resolve()` is consistent with the frontend; plan for explicit `AnonymousResourceCollection` handling only if partial reload/pagination changes.

## I. TypeScript Architecture Proposal

Recommended structure:

- `types/auth.ts`: explicit shared Auth/User and polymorphic userable types.
- `types/common.ts`: pagination, status, nullable text, office summary types.
- Module-specific payload files:
  - `types/evaluation.ts`
  - `types/assignment.ts`
  - `types/status.ts`
  - `types/user-management.ts`
  - `types/feedback.ts`
  - `types/export.ts`
- Page props interfaces remain local or in module payload files only if reused.

Composition rules:
- `PersonSummary`, `JabatanSummary`, `BiroSummary`, `EvaluatorSlot` as small reusable types.
- `PenugasanResult` and `EvaluationInstrument` as separate types; do not force them into one broad `Outsourcing` type.
- Remove `[key:string]:unknown` from core types once backend contracts are explicit.
- Add global shared props for `flash`, `auth`, `name`, and `sidebarOpen` rather than relying on `[key:string]:unknown`.
- Make all props with no alternative state explicitly required/nullable. Optional means genuinely omitted.
- Replace `any` in audited pages and child components; runtime unknown normalization can remain at the serializer boundary, not spread throughout props.

Laravel JSON names and TS names should match exactly; no frontend key aliasing should be required where a contract can be normalized consistently.

## J. Page-to-Contract Mapping

| Frontend page | Frontend data requirement | Backend source/query | Proposed Resource | Proposed TS type |
|---|---|---|---|---|
| Assignment | card + evaluator options | Dashboard service + active cycle | Assignment, evaluator option | `AssignmentPageProps` |
| Rekap hasil | result rows, filters, units | Rekap controller + engine | Rekap summary Resource | `RekapHasilPageProps` |
| Ranking | ranking groups, jabatan options | Ranking controller + engine | Ranking/position payload | `RankingPageProps` |
| Status | two status views | Status service + status Resources | Existing status Resources, narrowed | `StatusPenilaianPageProps` |
| Saran | feedback groups/options | Saran service | Feedback payload | `SaranPerbaikanPageProps` |
| Users | tab users/count/options | User controller | Map/DTO arrays or a dedicated Resource | `UserManagementPageProps` |
| Final detail | header/pilar/evaluator/reset | Outsourcing controller + engine | Final detail DTO | `NilaiAkhirProps` |
| Per-pilar | aspect/evaluator totals | Engine detail method | Detail-per-pilar DTO | `RekapNilaiProps` |
| Notes | penugasan/evaluator/note | Outsourcing relation load | Notes payload | `CatatanEvaluatorProps` |
| Per criteria | tabs/instrument/scores | Engine + Pilar query | Instrument/summary | `NilaiPerkriteriaProps` |
| Evaluator dashboard | assignments/history/results/user | Penugasan controller + engine | Assignment/history/result payload | `EvaluatorPageProps` |
| Evaluation form | OS/evaluator/instrument/notes | Penilaian create + engine | Evaluation form payload | `EvaluationFormProps` |
| View score | identities/instrument/notes | Penilaian create branch | View-score payload | `ViewScoreProps` |
| Export raw/status/saran | export-specific flat/nested | Controllers/services/Resources | Export DTO/array | Export prop types |
| Profile | verification/status/shared auth | Profile controller | None; shared auth | `ProfileProps` |
| Security | password/passkey/2FA | Security controller | Dedicated security payload | `SecurityProps` |
| Appearance | no dynamic props | Route inertia | None | no props |
| Auth | auth-state strings/config | Fortify provider | None | auth page prop types |

## K. Risks and Dependencies

- **Risk: behavior change**. Selective columns must retain primary keys, foreign keys, morph keys, pivot keys, UUID action keys, and existing status fields.
- **Risk: null active cycle**. `PenugasanController@home` dereferences `$siklusAktif->id`, but no active cycle guard is present. This must be preserved or explicitly resolved in Phase 2.
- **Risk: hard-coded cycle**. User counts use `siklus_id=1`; changing it to active cycle may alter behavior and must be coordinated with business acceptance.
- **Risk: polymorphic morph keys**. Any shared-auth or evaluator optimization must retain `userable_type/userable_id` and `nip` matching.
- **Risk: Indikator penilaian relation**. `Indikator::penilaian()` is `hasOne` even though a penilaian is unique by penugasan+indikator. Existing query filters by `penugasan_id`, so the contract must preserve exact selected score behavior.
- **Risk: exports**. Export pages perform browser download and history-back. Resource changes must not change ordering or sheet data semantics.
- **Risk: UI bugs**. Several date/score utilities and type mismatches already produce TypeScript errors; do not conflate them with data contract issues in a later implementation phase.
- **Unverified: live SQL**. Query counts and payload bytes require request instrumentation or Laravel Debugbar/Dusk/browser plus DB query listener.
- **Unverified: production data cardinality**. Ranking/pagination needs depend on actual active employee and assignment volume.
- **Unverified: database types**. Decimal/date/cast behavior should be confirmed from migrations and representative database rows.

## L. Implementation Plan for Phase 2

1. Establish shared/low-risk contracts:
   - shared auth/flash/sidebar types,
   - person/jabatan/biro base types,
   - explicit status/nullable enum definitions.
2. Introduce query boundaries for assignment and status pages:
   - narrow assignments/evaluator option columns,
   - enforce active cycle consistently,
   - verify existing Resources against exact JSON.
3. Revitalize evaluator flow:
   - evaluation form payload,
   - view score payload,
   - shared userability and assignment types.
4. Revitalize score transformation:
   - rekap,
   - final score,
   - per-pilar,
   - per-criterion,
   - ranking.
   - Preserve engine output and calculations exactly while narrowing eager loads.
5. Revitalize feedback and export:
   - Saran payload,
   - raw export,
   - status exports,
   - feedback types.
6. Revitalize user management:
   - first verify whether hard-coded cycle should remain,
   - split user tab payloads or narrow existing map outputs,
   - then align form submission fields.
7. Settings/auth:
   - add explicit props and shared flash types,
   - remove/deactivate `auth/register` only if route discovery confirms it is intentional legacy.
8. Remove obsolete/redundant fields and add partial reload contracts after a full test baseline.
9. Add request/contract tests and SQL query assertions for each module.

## M. Baseline Validation

Frontend:
- `npm run types:check` — fails currently. Observed first-party failures include Wayfinder generated route types, shared auth/flash unknowns, implicit anys, and missing score helpers.
- `npm run lint:check`
- `npm run format:check`
- `npm run build`
- `npm run build:ssr`

Backend:
- `php artisan test` — baseline: 54 tests, 43 passed, 228 assertions, 6 failed. Failures include registration page rendering 500 and are not evidence of this audit changing behavior.
- `php artisan route:list --except-vendor`
- `composer run lint:check`
- `composer run types:check` / `vendor/bin/phpstan.bat analyse --no-progress`
- PHPStan baseline is currently blocked by the configured 128 MB worker memory limit.

Existing test areas include auth, settings, image upload, dashboard, rekap filters, Penugasan dashboard service, Penilaian store, Saran export, and EvaluationEngine service.

## N. Count and Deliverable Summary

- Identified page files: 27.
- Backend-mapped active Inertia page/component names: 26 active or configured; `auth/register` is legacy and not currently routed.
- Existing Laravel Resources: 7 files plus existing API resources; audit found 5 domain page/status resources and 2 API resources.
- Countable explicitly/implicitly declared page props: 49 page-local and shared prop entries (including shared props); exact count depends on whether nested fields are counted as props. The recommended implementation should count nested fields in contract inventory rather than top-level props.

## O. Phase 2 Implementation Update

### O1. Implemented scope

- Added explicit shared and module contracts under `src/resources/js/types/contracts.ts`.
- Updated global Inertia shared prop typing for `name`, `auth`, `sidebarOpen`, and `flash`.
- Typed the assignment, rekap, ranking, status, feedback, user management, detail, evaluation, evaluator dashboard, export, and shared person payload families.
- Removed audited page-level `any` usage except for legacy normalization boundaries in the evaluation form, where `unknown` is narrowed before transformation.
- Typed relevant evaluated child components, including the shared view-score and export components.
- Added `RekapHasilResource` and `EvaluationFormResource`.
- Preserved existing status and assignment Resources and narrowed their backend relationship loading.
- Added selective eager loading and column lists to rekap, ranking, evaluation form, final detail, per-pilar/per-criteria prerequisites, assignment/status, Saran feedback, user management, and raw export paths.
- Narrowed shared polymorphic userable loading to required employee/outsourcing columns.
- Added active-cycle filtering to the outsourcing status Resource query.
- Preserved existing route names, methods, calculations, controller workflows, and form payload behavior.

### O2. Files added or changed

Key additions:
- `src/resources/js/types/contracts.ts`
- `src/app/Http/Resources/RekapHasilResource.php`
- `src/app/Http/Resources/EvaluationFormResource.php`

Major updates:
- `src/app/Http/Middleware/HandleInertiaRequests.php`
- `src/app/Http/Controllers/PenilaianController.php`
- `src/app/Http/Controllers/OutsourcingController.php`
- `src/app/Http/Controllers/UserController.php`
- `src/app/Services/Penilaian/EvaluationEngineService.php`
- `src/app/Services/Penilaian/SaranPerbaikanEvaluatorService.php`
- `src/app/Services/Penugasan/PenugasanDashboardService.php`
- Existing assignment/status Resources.
- Audited admin, evaluator, detail, export, and child components under `src/resources/js/pages` and `src/resources/js/components`.

### O3. Remaining any usage

No audited primary Inertia page prop destructuring or audited page-level payload mapping remains as `any`.

Verified remaining `any` findings:
- Wayfinder generated route code remains generated and is outside the data-contract prop scope.
- The evaluation form retains a justified runtime boundary around `evaluationData: unknown`, with narrow record parsing rather than page prop escape.
- Export/score child components now have explicit module contracts.

### O4. Known implementation limitations

- `RekapHasilResource` wraps already-calculated arrays rather than model serialization; this is intentional and preserves exact current calculation output.
- `EvaluationFormResource` wraps the existing evaluation payload instead of exposing model attributes.
- User management still preserves the hard-coded `siklus_id=1` behavior because changing it to active-cycle semantics would alter existing count behavior.
- Server-side pagination was not introduced to avoid changing existing UI/workflows.
- Live SQL measurement and production payload-byte measurement were not available in this execution.
- Auth/settings/export pages not previously requiring dynamic domain props were typed or left structurally unchanged where no payload contract existed.

### O5. Phase 2 validation

Executed successfully:
- `npm run types:check` — passes.
- `npm run build` — passes.
- `php artisan route:list --except-vendor` — 33 routes remain registered.
- PHP syntax lint of application controllers, middleware, Resources, and services — passes.
- Generated Wayfinder route types are now regenerated during build.

Executed unchanged baseline:
- `php artisan test` — 54 tests executed, 43 passed, 6 failed, with the same 54/43/228 baseline result. The failures remain present and include registration/auth rendering 500 responses. No newly introduced PHP syntax or route failure was observed.
