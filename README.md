# Portfolio Project Report (2107118)

## 1. Usage of HTML, CSS, and JavaScript  

### HTML (PHP pages with embedded HTML)  
**Public pages:**  
- `homepage.php`, `about.php`, `skills.php`, `projects.php`, `education.php`,  
  `achievements.php`, `certifications.php`, `contact.php`.  

**Admin pages:**  
- `admin_projects.php`, `admin_project_edit.php`, `admin_messages.php`,  
  `admin_certificates.php`, `admin_certificate_edit.php`,  
  `admin_achievements.php`, `admin_achievement_edit.php`.  

These files define the structure of the portfolio and admin panel.  

### CSS (`style.css`)  
- Controls theme, layout, and responsiveness.  
- Styles navigation, hero, about, education, skills, projects, contact sections.  
- Includes media queries, clamp-based font sizes, grid/flexbox layouts.  

### JavaScript (`app.js`)  
Progressive enhancements across the site:  
- **Mobile Navbar Toggle:** Opens/closes the hamburger menu on small screens.  
- **Scroll-to-Top Button:** Floating button appears after scrolling 300px, smoothly returns to top.  
- **Typing Animation:** Simulates typing for the hero tagline using `#tagline` (reads from `data-text` if present).  
- **Reveal on Scroll:** Elements with class `reveal` fade/slide in when entering viewport, with 1s delay and staggered +0.2s.  

---

## 2. Database CRUD Operations  

### Database Tables (inferred):  
- **admins** → `(id, username, password_hash)`  
- **projects** → `(id, title, description, links, tech, images, award)`  
- **messages** → `(id, name, email, message, ip_addr, is_read, created_at)`  
- **achievements** → `(id, title, description, image)`  
- **certificates** → `(id, title, issuer, image, link)`  

### CRUD by File  
- **Create:** `admin_projects.php`, `admin_achievements.php`, `admin_certificates.php`, `contact.php`.  
- **Read:** `projects.php`, `admin_projects.php`, `admin_messages.php`, `achievements.php`,  
  `certifications.php`, `education.php`.  
- **Update:** `admin_project_edit.php`, `admin_achievement_edit.php`,  
  `admin_certificate_edit.php`, `admin_messages.php`.  
- **Delete:** `admin_projects.php`, `admin_achievements.php`, `admin_certificates.php`,  
  `admin_messages.php`.  

---

## 3. Cookies and Sessions  

### Sessions  
- Managed by `auth.php` (`require_login` checks).  
- `login.php` sets `$_SESSION['admin_id']`.  
- Used to protect all admin pages.  

### Cookies  
- `login.php` sets a `remember_user` cookie to prefill username.  
- Option available to clear this cookie.  

---

## 4. Responsiveness for Mobile  

Handled by `style.css` using media queries and `clamp()`:  
- Navbar switches to hamburger at **max-width:900px**.  
- About section stacks image/text at **max-width:767px**.  
- Education cards stack vertically at **max-width:780px**.  
- Skills grid collapses at **max-width:900px**.  
- Projects grid auto-adjusts via `grid-template-columns`.  
- Contact form collapses to single column at **max-width:900px**.  
- Requires viewport meta tag:  

## Summary:  
HTML structures pages, CSS provides design + responsiveness, JavaScript adds interactivity 
(navbar, scroll-to-top, typing, reveal-on-scroll with delay). Database CRUD is implemented in 
admin pages and contact form. Sessions secure the admin panel, cookies store username. 
Responsiveness achieved with media queries and viewport meta tag.
