# Database Schema - PrestaGo

This document outlines the database structure for the PrestaGo application designed for JTI Polinema.

## Database Tables

### users

Stores user information across all roles (students, lecturers, administrators).

| Column | Type | Description |
|--------|------|-------------|
| id | bigint(20) unsigned | Primary key |
| name | varchar(255) | User's full name |
| email | varchar(255) | Unique email address |
| email_verified_at | timestamp | When email was verified |
| password | varchar(255) | Hashed password |
| role | varchar(20) | User role (admin, dosen, mahasiswa) |
| nim | varchar(20) | Student ID number (for students) |
| program_studi_id | bigint(20) unsigned | Foreign key to program_studi |
| remember_token | varchar(100) | Remember me token |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

### user_skills

Maps skills to users (for matching with competitions).

| Column | Type | Description |
|--------|------|-------------|
| id | bigint(20) unsigned | Primary key |
| user_id | bigint(20) unsigned | Foreign key to users |
| skill_id | bigint(20) unsigned | Foreign key to skills |
| proficiency_level | integer | Skill level (1-5) |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

### skills

Master table of skills for matching with competitions.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint(20) unsigned | Primary key |
| name | varchar(255) | Skill name |
| category | varchar(100) | Skill category |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

### achievements

Stores all student achievements.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint(20) unsigned | Primary key |
| user_id | bigint(20) unsigned | Foreign key to users table |
| title | varchar(255) | Achievement title |
| description | text | Detailed description |
| competition_name | varchar(255) | Name of competition/event |
| competition_id | bigint(20) unsigned | Foreign key to competitions (if applicable) |
| type | varchar(50) | Achievement type (academic, technology, arts, sports, entrepreneurship) |
| level | varchar(50) | Achievement level (international, national, regional) |
| date | date | Date of achievement |
| status | varchar(20) | Verification status (pending, verified, rejected) |
| verified_by | bigint(20) unsigned | User ID who verified the achievement |
| verified_at | timestamp | When achievement was verified |
| rejected_reason | text | Reason if rejected |
| period_id | bigint(20) unsigned | Academic period when achieved |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

### attachments

Stores files related to achievements.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint(20) unsigned | Primary key |
| achievement_id | bigint(20) unsigned | Foreign key to achievements |
| file_name | varchar(255) | Original file name |
| file_path | varchar(255) | Path to stored file |
| file_type | varchar(50) | MIME type of file |
| file_size | integer | Size in bytes |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

### program_studi

Stores academic programs/departments.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint(20) unsigned | Primary key |
| name | varchar(255) | Name of program/department |
| code | varchar(20) | Department code |
| faculty | varchar(255) | Faculty name |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

### competitions

Stores information about competitions available for students.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint(20) unsigned | Primary key |
| name | varchar(255) | Competition name |
| description | text | Detailed description |
| organizer | varchar(255) | Competition organizer |
| level | varchar(50) | Competition level (international, national, regional) |
| type | varchar(50) | Competition type/category |
| registration_start | date | Registration start date |
| registration_end | date | Registration deadline |
| competition_date | date | Competition date |
| registration_link | varchar(255) | URL for registration |
| requirements | text | Eligibility and submission requirements |
| status | varchar(20) | Competition status (upcoming, ongoing, completed) |
| verified | boolean | Whether verified by admin |
| added_by | bigint(20) unsigned | User who added the competition |
| period_id | bigint(20) unsigned | Academic period reference |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

### competition_skills

Maps required skills to competitions.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint(20) unsigned | Primary key |
| competition_id | bigint(20) unsigned | Foreign key to competitions |
| skill_id | bigint(20) unsigned | Foreign key to skills |
| importance_level | integer | How important this skill is (1-5) |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

### recommendations

Stores competition recommendations for students.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint(20) unsigned | Primary key |
| competition_id | bigint(20) unsigned | Foreign key to competitions |
| user_id | bigint(20) unsigned | Student being recommended |
| match_score | decimal(5,2) | DSS recommendation score (0-100) |
| status | varchar(20) | Status (pending, accepted, rejected) |
| recommended_by | varchar(20) | Source (system, lecturer, admin) |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

### competition_participants

Tracks students participating in competitions.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint(20) unsigned | Primary key |
| competition_id | bigint(20) unsigned | Foreign key to competitions |
| user_id | bigint(20) unsigned | Student participating |
| mentor_id | bigint(20) unsigned | Lecturer assigned as mentor |
| status | varchar(50) | Status (registered, participating, completed) |
| notes | text | Additional notes |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

### lecturer_mentorships

Maps lecturers to their mentored competitions.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint(20) unsigned | Primary key |
| dosen_id | bigint(20) unsigned | Lecturer who is mentoring |
| competition_id | bigint(20) unsigned | Competition being mentored |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

### periods

Stores academic periods/semesters.

| Column | Type | Description |
|--------|------|-------------|
| id | bigint(20) unsigned | Primary key |
| name | varchar(255) | Period name (e.g., "Semester Ganjil 2023/2024") |
| start_date | date | Period start date |
| end_date | date | Period end date |
| is_active | boolean | Whether period is currently active |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |

## Relationships

- **Users → Program Studi**: Many-to-one relationship
- **Users → Skills**: Many-to-many relationship through user_skills
- **Achievements → Users**: Many-to-one relationship (student who earned achievement)
- **Attachments → Achievements**: Many-to-one relationship
- **Competitions → Skills**: Many-to-many relationship through competition_skills
- **Competitions → Users (added_by)**: Many-to-one relationship
- **Recommendations → Users**: Many-to-one relationship
- **Recommendations → Competitions**: Many-to-one relationship
- **Competition Participants → Users**: Many-to-one relationship
- **Competition Participants → Competitions**: Many-to-one relationship
- **Competition Participants → Users (mentor_id)**: Many-to-one relationship
- **Lecturer Mentorships → Users (dosen_id)**: Many-to-one relationship
- **Lecturer Mentorships → Competitions**: Many-to-one relationship
- **Achievements → Periods**: Many-to-one relationship
- **Competitions → Periods**: Many-to-one relationship

## Entity Relationship Diagram

```
+-------------+       +---------------+       +-------------+
|             |       |               |       |             |
|   users     +-------+ achievements  +-------+ attachments |
|             |       |               |       |             |
+------+------+       +-------+-------+       +-------------+
       |                      |
       |                      |
+------v------+               |
|             |               |
| program_studi              |
|             |               |
+-------------+               |
                              |
+-------------+       +-------v-------+
|             |       |               |
|   periods   +-------+ competitions  |
|             |       |               |
+------+------+       +------+--------+
       |                     |
       |                     |
+------v------+      +-------v--------+
|             |      |                |
|   skills    +------+ recommendations|
|             |      |                |
+-------------+      +----------------+
``` 