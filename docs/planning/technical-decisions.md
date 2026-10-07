# Technical Decisions

## 1. Routing Strategy: Query String (`?page=X&action=Y`)

**Decision:** Use query string routing instead of URL path routing (`/projects/create`).

**Rationale:**
- Simpler to implement without Apache mod_rewrite complications in Docker
- Easier to explain to trainer during technical defense
- No `.htaccess` dependency issues across environments
- Fully functional and meets all routing requirements

**Trade-off:** URLs are less "clean" than REST-style paths, but functionality is identical.

---

## 2. PDO Singleton Pattern

**Decision:** Use a static singleton `Database::getInstance()` for PDO.

**Rationale:**
- Prevents multiple redundant connections per request
- Simple to understand and trace
- No ORM or connection pooling needed at this scale

---

## 3. Password Security: BCrypt via `password_hash()`

**Decision:** Use `password_hash($pass, PASSWORD_BCRYPT)` for all passwords.

**Rationale:** Required by brief (AUTH-01). BCrypt is the PHP standard, automatically salted.

---

## 4. Soft Delete for Users

**Decision:** Set `is_active = 0` instead of `DELETE FROM users`.

**Rationale:** Required by brief (USR-01). Preserves referential integrity since tasks reference users via FK.

---

## 5. Archive Instead of Delete for Projects

**Decision:** Projects with tasks can only be archived, not deleted.

**Rationale:** Required by brief (PRJ-01). Preserves task history and relational data.

---

## 6. Server-Side Authorization on Every Controller Method

**Decision:** Call `Auth::requireAdmin()` or `Auth::requireLogin()` at the start of every controller method.

**Rationale:** Required by brief (Security section). Never trust frontend to enforce access control.

---

## 7. Pagination: SQL `LIMIT/OFFSET` (not client-side)

**Decision:** All pagination is done in the database query with `LIMIT 10 OFFSET N`.

**Rationale:** Required by brief (FIND-01). Client-side pagination of pre-loaded data is explicitly prohibited.

---

## 8. Filter State Persistence via GET Parameters

**Decision:** All filters are submitted as GET parameters and included in pagination links.

**Rationale:** Required by brief — "Search/filter must remain active when user changes page."

---

## 9. XSS Prevention: `htmlspecialchars()` on All Output

**Decision:** All user-supplied data displayed in HTML is passed through `htmlspecialchars($val, ENT_QUOTES, 'UTF-8')`.

**Rationale:** Required by brief (Security section). Applied consistently in all view files.

---

## 10. View Helper Functions (Local `e()` and badge functions)

**Decision:** Define small helper functions like `e()` (escape) locally in each view file.

**Rationale:** Keeps views simple without a full template engine. Easy to understand, easy to explain.
