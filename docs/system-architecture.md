# System Architecture - PrestaGo

This document outlines the architecture and components of the PrestaGo system for JTI Polinema.

## Overview

PrestaGo is developed using a monolithic architecture, with all components (frontend, backend, and database) integrated into a single application unit. This approach was chosen to simplify development, deployment, and maintenance in the initial phase of the system.

## Technology Stack

The system is built using the following technologies:

- **Backend Framework**: Laravel 10
- **Programming Language**: PHP 8
- **Frontend Framework**: Bootstrap 4/5 with TailwindCSS
- **Database**: MySQL/MariaDB
- **Authentication**: Laravel's built-in authentication system
- **Decision Support System**: Custom DSS implementation for competition participant recommendations

## System Components

### 1. User Management Module

Handles user registration, authentication, and role-based access control for:
- Admin (Academic staff)
- Students
- Lecturers

### 2. Achievement Recording Module

Manages the recording, verification, and storage of student achievements:
- Achievement data entry with evidence upload capability
- Achievement categorization (academic, technology, arts, sports, entrepreneurship)
- Verification workflow for admins to approve or reject achievement entries

### 3. Competition Management Module

Handles the management of competition information:
- Competition data entry and categorization
- Competition period management
- Registration links and deadlines
- Skill area categorization

### 4. Recommendation System Module

Implements the Decision Support System (DSS) for recommending:
- Suitable students for specific competitions based on their achievement history, skills, and interests
- Suitable lecturers for competition mentoring

### 5. Reporting and Analytics Module

Provides data visualization and reporting functionality:
- Achievement statistics by category, competition level, and academic year
- Trending analysis of student achievements
- Export capabilities for documentation and accreditation purposes

## Data Flow

1. **User Registration and Profile Management**:
   - Users register with appropriate roles
   - Students and lecturers complete their profiles with skills and interests

2. **Achievement Recording Process**:
   - Students enter achievement details and upload evidence
   - Admins/Lecturers verify the submitted achievements
   - Verified achievements are stored in the database

3. **Competition Management**:
   - Admins, lecturers, or students add new competition information
   - Admins verify competition details
   - Competition opportunities are displayed to relevant users

4. **Recommendation Process**:
   - The system analyzes student profiles and achievement history
   - Matching algorithms recommend suitable students for competitions
   - Notifications are sent to recommended students and supervisors

5. **Reporting and Analytics**:
   - Admins generate reports on student achievements
   - Statistics and visualizations are displayed on the admin dashboard

## System Constraints

As specified in the project scope, the system:
- Is limited to students, lecturers, and admins from JTI Polinema
- Only records achievements obtained during study at Polinema
- Does not evaluate competition quality, focusing only on recording achievements and recommending participants
- Supports various achievement categories (academic, technology, arts, sports, entrepreneurship)

## Deployment Architecture

The monolithic application will be deployed on a web server with:
- PHP 8 runtime environment
- MySQL/MariaDB database server
- Web server (Apache/Nginx)
- File storage for achievement evidence and related documents 