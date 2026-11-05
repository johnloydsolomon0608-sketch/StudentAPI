# Employee Management API
Solomon John Loyd A. · BSIT-3B · IT Free Elective 2

## Patakbuhin sa Windows
1. Extract All ang ZIP. Open ang folder na may START.cmd sa VS Code. Puwede sa Documents.
2. Start MySQL sa XAMPP (XAMPP Control Panel → Start sa MySQL). Kailangan ang PHP 8.2+ na may pdo_mysql, mbstring, OpenSSL at XML. Ang XAMPP default ay PHP 8.1 (mababa para sa Laravel 12), kaya ang launcher ay naghahanap ng PHP 8.2 (hal. C:\php82) o gagamitin ang XAMPP_HOME kung set. Kung walang PHP 8.2, i-download mula sa windows.php.net.
3. Press Ctrl+Shift+B sa VS Code, o double-click START.cmd. Sa unang run kailangan ng internet para sa Composer/Laravel 12/Sanctum dependencies. Gagawin awtomatikong ang .env mula sa .env.example, ang database na solomon_johnloyd_itfree2, tables, at seed data. Kung ang MySQL mo ay may password, i-edit ang DB_PASSWORD sa .env bago mag-run.
4. Hintayin ang "Ready." message. Buksan ang http://127.0.0.1:8000/api/students. Iwanang bukas ang terminal. Ctrl+C para ihinto. Sa susunod na session, start MySQL at ulitin ang step 3.
5. Postman Desktop → Import → piliin ang postman/Employee_API.postman_collection.json. Open collection → Run collection → Run. Sunod-sunod ang requests. Awtomatikong nagsa-save ng token at IDs ang collection. Para mag-test nang paisa-isa, sundin ang pagkakasunod mula sa taas.

## Database at configuration
Awtomatikong gagawin ang solomon_johnloyd_itfree2 database, tables at 12 sample employees. Default connection: 127.0.0.1, port 3306, root, blank password. Kung custom ang MySQL settings mo, kopyahin ang .env.example bilang .env at i-edit bago mag-run. Kailangan ng permission sa paggawa ng database, o gumawa muna ng empty database na pareho ang pangalan. Sa phpMyAdmin, tingnan ang employees, departments, users, personal_access_tokens at migrations. Walang kailangang SQL import. Hindi ginagamit ang migrate:fresh o db:wipe; ang existing records ay pinapanatili.

## Activities
- Week 2: GET /api/students, tatlong student records.
- Week 3: postman/openapi.json, versioned employee API design.
- Week 4: Employee CRUD and validation. May GET, POST, PUT, PATCH at DELETE.
- Week 5: Employee belongs to Department. Subukan ang search, department_id, department, page at per_page.
- Week 6: Register, login, profile, logout at logout-all gamit ang Sanctum. Kailangan ng Bearer token sa employee at department routes, kasama ang /api/v1/employees. Registration creates a new lab user on each full collection run. Logout-all revokes its remaining tokens.

## Kapag may error
- Connection refused / 2002: Start MySQL sa XAMPP. Check port sa .env.
- Access denied / 1045: Check DB_USERNAME at DB_PASSWORD sa .env.
- Missing extension: Buksan ang php.ini ng ginagamit na PHP, enable ang extension na nakalista sa error at patakbuhin ulit ang launcher.
- PHP not found: Set XAMPP_HOME sa XAMPP folder, o itakda ang C:\php82 para sa PHP 8.2.
- Port 8000 busy: Hinto ang mas lumang API server bago muling mag-run ng START.cmd.
- Postman connection error: Check muna ang /api/students sa browser. Gamitin ang Postman Desktop at iwanang bukas ang server terminal.
- 401 pagkatapos mag-logout: Run Login ulit, tapos ang protected requests.
- Para sa screenshots, OBS recording at GitHub submission: gamitin ang sarili mong actual run at repository ayon sa original guide.

## Nagbabagong file sa setup
- setup/start.ps1: Nagdagdag ng PHP 8.2 detection (C:\php82 flat at XAMPP structure), version check (8.2+), at nagpalit ng `artisan serve` sa built-in `php -S` server para sa reliably na pag-start sa Windows.
- .env: Nabuo mula sa .env.example (DB: solomon_johnloyd_itfree2). APP_KEY ay pinanatili/nabuo awtomatikong.
