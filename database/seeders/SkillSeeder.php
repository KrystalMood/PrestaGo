<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SkillModel;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            // Programming Languages
            ['name' => 'Java', 'category' => 'Programming Language'],
            ['name' => 'Python', 'category' => 'Programming Language'],
            ['name' => 'JavaScript', 'category' => 'Programming Language'],
            ['name' => 'PHP', 'category' => 'Programming Language'],
            ['name' => 'C++', 'category' => 'Programming Language'],
            ['name' => 'C#', 'category' => 'Programming Language'],
            ['name' => 'Ruby', 'category' => 'Programming Language'],
            ['name' => 'Go', 'category' => 'Programming Language'],
            ['name' => 'Swift', 'category' => 'Programming Language'],
            ['name' => 'TypeScript', 'category' => 'Programming Language'],
            ['name' => 'Kotlin', 'category' => 'Programming Language'],
            ['name' => 'Rust', 'category' => 'Programming Language'],
            ['name' => 'Scala', 'category' => 'Programming Language'],
            ['name' => 'Dart', 'category' => 'Programming Language'],
            ['name' => 'R', 'category' => 'Programming Language'],
            ['name' => 'Perl', 'category' => 'Programming Language'],
            ['name' => 'Haskell', 'category' => 'Programming Language'],
            ['name' => 'Julia', 'category' => 'Programming Language'],
            ['name' => 'Lua', 'category' => 'Programming Language'],
            ['name' => 'Groovy', 'category' => 'Programming Language'],
            ['name' => 'COBOL', 'category' => 'Programming Language'],
            ['name' => 'Fortran', 'category' => 'Programming Language'],
            ['name' => 'Assembly', 'category' => 'Programming Language'],
            ['name' => 'Objective-C', 'category' => 'Programming Language'],
            ['name' => 'VB.NET', 'category' => 'Programming Language'],
            ['name' => 'Delphi', 'category' => 'Programming Language'],
            ['name' => 'MATLAB', 'category' => 'Programming Language'],
            ['name' => 'Clojure', 'category' => 'Programming Language'],
            ['name' => 'Elixir', 'category' => 'Programming Language'],
            ['name' => 'F#', 'category' => 'Programming Language'],
            
            // Web Development Frameworks
            ['name' => 'Laravel', 'category' => 'Web Framework'],
            ['name' => 'React', 'category' => 'Web Framework'],
            ['name' => 'Angular', 'category' => 'Web Framework'],
            ['name' => 'Vue.js', 'category' => 'Web Framework'],
            ['name' => 'Django', 'category' => 'Web Framework'],
            ['name' => 'Spring Boot', 'category' => 'Web Framework'],
            ['name' => 'Express.js', 'category' => 'Web Framework'],
            ['name' => 'Ruby on Rails', 'category' => 'Web Framework'],
            ['name' => 'Flask', 'category' => 'Web Framework'],
            ['name' => 'ASP.NET Core', 'category' => 'Web Framework'],
            ['name' => 'Symfony', 'category' => 'Web Framework'],
            ['name' => 'CodeIgniter', 'category' => 'Web Framework'],
            ['name' => 'Svelte', 'category' => 'Web Framework'],
            ['name' => 'Next.js', 'category' => 'Web Framework'],
            ['name' => 'Nuxt.js', 'category' => 'Web Framework'],
            ['name' => 'FastAPI', 'category' => 'Web Framework'],
            ['name' => 'Blazor', 'category' => 'Web Framework'],
            ['name' => 'CakePHP', 'category' => 'Web Framework'],
            ['name' => 'Ember.js', 'category' => 'Web Framework'],
            ['name' => 'Meteor', 'category' => 'Web Framework'],
            
            // Mobile Development
            ['name' => 'Flutter', 'category' => 'Mobile Development'],
            ['name' => 'React Native', 'category' => 'Mobile Development'],
            ['name' => 'Xamarin', 'category' => 'Mobile Development'],
            ['name' => 'Ionic', 'category' => 'Mobile Development'],
            ['name' => 'Android SDK', 'category' => 'Mobile Development'],
            ['name' => 'iOS Development', 'category' => 'Mobile Development'],
            ['name' => 'Kotlin Multiplatform', 'category' => 'Mobile Development'],
            ['name' => 'NativeScript', 'category' => 'Mobile Development'],
            ['name' => 'SwiftUI', 'category' => 'Mobile Development'],
            ['name' => 'Jetpack Compose', 'category' => 'Mobile Development'],
            
            // Database
            ['name' => 'MySQL', 'category' => 'Database'],
            ['name' => 'PostgreSQL', 'category' => 'Database'],
            ['name' => 'MongoDB', 'category' => 'Database'],
            ['name' => 'Oracle', 'category' => 'Database'],
            ['name' => 'SQL Server', 'category' => 'Database'],
            ['name' => 'SQLite', 'category' => 'Database'],
            ['name' => 'Redis', 'category' => 'Database'],
            ['name' => 'Cassandra', 'category' => 'Database'],
            ['name' => 'MariaDB', 'category' => 'Database'],
            ['name' => 'DynamoDB', 'category' => 'Database'],
            ['name' => 'Firebase Firestore', 'category' => 'Database'],
            ['name' => 'Neo4j', 'category' => 'Database'],
            ['name' => 'Elasticsearch', 'category' => 'Database'],
            ['name' => 'CouchDB', 'category' => 'Database'],
            ['name' => 'Supabase', 'category' => 'Database'],
            ['name' => 'Fauna', 'category' => 'Database'],
            
            // DevOps & Cloud
            ['name' => 'Docker', 'category' => 'DevOps'],
            ['name' => 'Kubernetes', 'category' => 'DevOps'],
            ['name' => 'Jenkins', 'category' => 'DevOps'],
            ['name' => 'AWS', 'category' => 'Cloud Computing'],
            ['name' => 'Azure', 'category' => 'Cloud Computing'],
            ['name' => 'Google Cloud', 'category' => 'Cloud Computing'],
            ['name' => 'Terraform', 'category' => 'Infrastructure as Code'],
            ['name' => 'Ansible', 'category' => 'Infrastructure as Code'],
            ['name' => 'GitHub Actions', 'category' => 'CI/CD'],
            ['name' => 'GitLab CI', 'category' => 'CI/CD'],
            ['name' => 'Travis CI', 'category' => 'CI/CD'],
            ['name' => 'Prometheus', 'category' => 'Monitoring'],
            ['name' => 'Grafana', 'category' => 'Monitoring'],
            ['name' => 'ELK Stack', 'category' => 'Logging'],
            ['name' => 'Nginx', 'category' => 'Web Server'],
            ['name' => 'Apache', 'category' => 'Web Server'],
            ['name' => 'Serverless Framework', 'category' => 'Serverless Computing'],
            ['name' => 'Helm', 'category' => 'Kubernetes Package Manager'],
            ['name' => 'Istio', 'category' => 'Service Mesh'],
            ['name' => 'Pulumi', 'category' => 'Infrastructure as Code'],
            
            // AI & Machine Learning
            ['name' => 'TensorFlow', 'category' => 'Machine Learning'],
            ['name' => 'PyTorch', 'category' => 'Machine Learning'],
            ['name' => 'Scikit-learn', 'category' => 'Machine Learning'],
            ['name' => 'Keras', 'category' => 'Machine Learning'],
            ['name' => 'Natural Language Processing', 'category' => 'Machine Learning'],
            ['name' => 'Computer Vision', 'category' => 'Machine Learning'],
            ['name' => 'Deep Learning', 'category' => 'Machine Learning'],
            ['name' => 'Reinforcement Learning', 'category' => 'Machine Learning'],
            ['name' => 'Data Mining', 'category' => 'Data Science'],
            ['name' => 'Pandas', 'category' => 'Data Science'],
            ['name' => 'NumPy', 'category' => 'Data Science'],
            ['name' => 'SciPy', 'category' => 'Data Science'],
            ['name' => 'Apache Spark', 'category' => 'Big Data'],
            ['name' => 'Hadoop', 'category' => 'Big Data'],
            ['name' => 'Big Data Analytics', 'category' => 'Big Data'],
            ['name' => 'Generative AI', 'category' => 'Artificial Intelligence'],
            ['name' => 'OpenAI API', 'category' => 'Artificial Intelligence'],
            ['name' => 'Speech Recognition', 'category' => 'Machine Learning'],
            ['name' => 'Machine Learning Operations (MLOps)', 'category' => 'Machine Learning'],
            
            // Security
            ['name' => 'Cybersecurity', 'category' => 'Security'],
            ['name' => 'Penetration Testing', 'category' => 'Security'],
            ['name' => 'Network Security', 'category' => 'Security'],
            ['name' => 'Ethical Hacking', 'category' => 'Security'],
            ['name' => 'Security Auditing', 'category' => 'Security'],
            ['name' => 'Cryptography', 'category' => 'Security'],
            ['name' => 'OAuth 2.0', 'category' => 'Security'],
            ['name' => 'Web Application Security', 'category' => 'Security'],
            ['name' => 'OWASP', 'category' => 'Security'],
            ['name' => 'Incident Response', 'category' => 'Security'],
            ['name' => 'Vulnerability Assessment', 'category' => 'Security'],
            ['name' => 'Security Information and Event Management (SIEM)', 'category' => 'Security'],
            
            // Frontend
            ['name' => 'HTML5', 'category' => 'Frontend'],
            ['name' => 'CSS3', 'category' => 'Frontend'],
            ['name' => 'SASS/SCSS', 'category' => 'Frontend'],
            ['name' => 'Tailwind CSS', 'category' => 'Frontend'],
            ['name' => 'Bootstrap', 'category' => 'Frontend'],
            ['name' => 'Material UI', 'category' => 'Frontend'],
            ['name' => 'Chakra UI', 'category' => 'Frontend'],
            ['name' => 'Web Components', 'category' => 'Frontend'],
            ['name' => 'Redux', 'category' => 'Frontend'],
            ['name' => 'MobX', 'category' => 'Frontend'],
            ['name' => 'GraphQL', 'category' => 'API'],
            ['name' => 'Apollo Client', 'category' => 'Frontend'],
            ['name' => 'Webpack', 'category' => 'Frontend'],
            ['name' => 'Vite', 'category' => 'Frontend'],
            ['name' => 'Jest', 'category' => 'Testing'],
            ['name' => 'Cypress', 'category' => 'Testing'],
            ['name' => 'Storybook', 'category' => 'Frontend'],
            ['name' => 'Progressive Web Apps (PWA)', 'category' => 'Frontend'],
            ['name' => 'Responsive Web Design', 'category' => 'Frontend'],
            
            // Backend
            ['name' => 'RESTful API Design', 'category' => 'Backend'],
            ['name' => 'GraphQL API', 'category' => 'Backend'],
            ['name' => 'Microservices', 'category' => 'Architecture'],
            ['name' => 'Authentication & Authorization', 'category' => 'Backend'],
            ['name' => 'WebSockets', 'category' => 'Backend'],
            ['name' => 'gRPC', 'category' => 'Backend'],
            ['name' => 'Message Queues', 'category' => 'Backend'],
            ['name' => 'RabbitMQ', 'category' => 'Message Broker'],
            ['name' => 'Kafka', 'category' => 'Message Broker'],
            ['name' => 'Serverless Architecture', 'category' => 'Architecture'],
            ['name' => 'Event-Driven Architecture', 'category' => 'Architecture'],
            ['name' => 'Domain-Driven Design', 'category' => 'Architecture'],
            ['name' => 'ORM (Object-Relational Mapping)', 'category' => 'Backend'],
            ['name' => 'Cache Management', 'category' => 'Backend'],
            ['name' => 'API Gateway', 'category' => 'Backend'],
            
            // QA & Testing
            ['name' => 'Unit Testing', 'category' => 'Testing'],
            ['name' => 'Integration Testing', 'category' => 'Testing'],
            ['name' => 'End-to-End Testing', 'category' => 'Testing'],
            ['name' => 'Test-Driven Development (TDD)', 'category' => 'Testing'],
            ['name' => 'Behavior-Driven Development (BDD)', 'category' => 'Testing'],
            ['name' => 'Selenium', 'category' => 'Testing'],
            ['name' => 'Postman', 'category' => 'API Testing'],
            ['name' => 'JUnit', 'category' => 'Testing'],
            ['name' => 'Mocha', 'category' => 'Testing'],
            ['name' => 'Performance Testing', 'category' => 'Testing'],
            ['name' => 'Load Testing', 'category' => 'Testing'],
            ['name' => 'Automated Testing', 'category' => 'Testing'],
            
            // Game Development
            ['name' => 'Unity', 'category' => 'Game Development'],
            ['name' => 'Unreal Engine', 'category' => 'Game Development'],
            ['name' => 'Godot', 'category' => 'Game Development'],
            ['name' => 'Game Design', 'category' => 'Game Development'],
            ['name' => '3D Modeling', 'category' => 'Game Development'],
            ['name' => 'Game Physics', 'category' => 'Game Development'],
            ['name' => 'Mobile Game Development', 'category' => 'Game Development'],
            ['name' => 'AR/VR Development', 'category' => 'Game Development'],
            
            // Design
            ['name' => 'UI/UX Design', 'category' => 'Design'],
            ['name' => 'Figma', 'category' => 'Design'],
            ['name' => 'Adobe XD', 'category' => 'Design'],
            ['name' => 'Sketch', 'category' => 'Design'],
            ['name' => 'Photoshop', 'category' => 'Design'],
            ['name' => 'Illustrator', 'category' => 'Design'],
            ['name' => 'InDesign', 'category' => 'Design'],
            ['name' => 'User Research', 'category' => 'Design'],
            ['name' => 'Wireframing', 'category' => 'Design'],
            ['name' => 'Prototyping', 'category' => 'Design'],
            ['name' => 'Interaction Design', 'category' => 'Design'],
            ['name' => 'Visual Design', 'category' => 'Design'],
            ['name' => 'Information Architecture', 'category' => 'Design'],
            
            // IoT & Embedded
            ['name' => 'IoT Development', 'category' => 'IoT'],
            ['name' => 'Embedded Systems', 'category' => 'IoT'],
            ['name' => 'Arduino', 'category' => 'IoT'],
            ['name' => 'Raspberry Pi', 'category' => 'IoT'],
            ['name' => 'MQTT', 'category' => 'IoT'],
            ['name' => 'Bluetooth Low Energy (BLE)', 'category' => 'IoT'],
            ['name' => 'Sensor Integration', 'category' => 'IoT'],
            ['name' => 'Edge Computing', 'category' => 'IoT'],
            
            // Soft Skills
            ['name' => 'Public Speaking', 'category' => 'Soft Skill'],
            ['name' => 'Team Leadership', 'category' => 'Soft Skill'],
            ['name' => 'Project Management', 'category' => 'Soft Skill'],
            ['name' => 'Problem Solving', 'category' => 'Soft Skill'],
            ['name' => 'Critical Thinking', 'category' => 'Soft Skill'],
            ['name' => 'Communication', 'category' => 'Soft Skill'],
            ['name' => 'Time Management', 'category' => 'Soft Skill'],
            ['name' => 'Adaptability', 'category' => 'Soft Skill'],
            ['name' => 'Teamwork', 'category' => 'Soft Skill'],
            ['name' => 'Conflict Resolution', 'category' => 'Soft Skill'],
            ['name' => 'Emotional Intelligence', 'category' => 'Soft Skill'],
            ['name' => 'Negotiation', 'category' => 'Soft Skill'],
            ['name' => 'Creativity', 'category' => 'Soft Skill'],
            ['name' => 'Decision Making', 'category' => 'Soft Skill'],
            ['name' => 'Interpersonal Skills', 'category' => 'Soft Skill'],
            ['name' => 'Strategic Planning', 'category' => 'Soft Skill'],
            ['name' => 'Innovation', 'category' => 'Soft Skill'],
            ['name' => 'Growth Mindset', 'category' => 'Soft Skill'],
            
            // Project Management
            ['name' => 'Scrum', 'category' => 'Project Management'],
            ['name' => 'Agile Methodology', 'category' => 'Project Management'],
            ['name' => 'Kanban', 'category' => 'Project Management'],
            ['name' => 'Waterfall Methodology', 'category' => 'Project Management'],
            ['name' => 'JIRA', 'category' => 'Project Management'],
            ['name' => 'Trello', 'category' => 'Project Management'],
            ['name' => 'Asana', 'category' => 'Project Management'],
            ['name' => 'Risk Management', 'category' => 'Project Management'],
            ['name' => 'Budgeting', 'category' => 'Project Management'],
            ['name' => 'Stakeholder Management', 'category' => 'Project Management'],
            ['name' => 'Product Roadmapping', 'category' => 'Project Management'],
            ['name' => 'Product Management', 'category' => 'Project Management'],
            
            // Academic Skills
            ['name' => 'Research Methods', 'category' => 'Academic'],
            ['name' => 'Academic Writing', 'category' => 'Academic'],
            ['name' => 'Data Analysis', 'category' => 'Academic'],
            ['name' => 'Statistical Analysis', 'category' => 'Academic'],
            ['name' => 'Literature Review', 'category' => 'Academic'],
            ['name' => 'Case Study Development', 'category' => 'Academic'],
            ['name' => 'Scientific Method', 'category' => 'Academic'],
            ['name' => 'Grant Writing', 'category' => 'Academic'],
            ['name' => 'Peer Review', 'category' => 'Academic'],
            
            // Industry-specific
            ['name' => 'Healthcare IT', 'category' => 'Industry'],
            ['name' => 'Fintech', 'category' => 'Industry'],
            ['name' => 'EdTech', 'category' => 'Industry'],
            ['name' => 'E-commerce', 'category' => 'Industry'],
            ['name' => 'GIS (Geographic Information Systems)', 'category' => 'Industry'],
            ['name' => 'Digital Marketing', 'category' => 'Industry'],
            ['name' => 'Social Media Management', 'category' => 'Industry'],
            ['name' => 'Content Creation', 'category' => 'Industry'],
            ['name' => 'Search Engine Optimization (SEO)', 'category' => 'Industry'],
            ['name' => 'Video Production', 'category' => 'Industry'],
            ['name' => 'Blockchain Development', 'category' => 'Industry'],
            ['name' => 'Smart Contract Development', 'category' => 'Industry'],
            ['name' => 'Augmented Reality', 'category' => 'Industry'],
            ['name' => 'Virtual Reality', 'category' => 'Industry'],
            ['name' => 'Robotics', 'category' => 'Industry'],
            ['name' => 'Automation', 'category' => 'Industry'],

            // Business & Management
            ['name' => 'Business Analysis', 'category' => 'Business & Management'],
            ['name' => 'Business Development', 'category' => 'Business & Management'],
            ['name' => 'Market Research', 'category' => 'Business & Management'],
            ['name' => 'Financial Analysis', 'category' => 'Business & Management'],
            ['name' => 'Accounting', 'category' => 'Business & Management'],
            ['name' => 'Human Resources', 'category' => 'Business & Management'],
            ['name' => 'Supply Chain Management', 'category' => 'Business & Management'],
            ['name' => 'Operations Management', 'category' => 'Business & Management'],
            ['name' => 'Sales & Marketing', 'category' => 'Business & Management'],
            ['name' => 'Customer Relationship Management (CRM)', 'category' => 'Business & Management'],
            ['name' => 'Entrepreneurship', 'category' => 'Business & Management'],
            ['name' => 'Business Intelligence', 'category' => 'Business & Management'],

            // Creative Arts
            ['name' => 'Creative Writing', 'category' => 'Creative Arts'],
            ['name' => 'Copywriting', 'category' => 'Creative Arts'],
            ['name' => 'Journalism', 'category' => 'Creative Arts'],
            ['name' => 'Music Composition', 'category' => 'Creative Arts'],
            ['name' => 'Music Production', 'category' => 'Creative Arts'],
            ['name' => 'Photography', 'category' => 'Creative Arts'],
            ['name' => 'Videography', 'category' => 'Creative Arts'],
            ['name' => 'Animation', 'category' => 'Creative Arts'],
            ['name' => 'Graphic Illustration', 'category' => 'Creative Arts'],
            ['name' => 'Fine Arts', 'category' => 'Creative Arts'],

            // Languages
            ['name' => 'English', 'category' => 'Language'],
            ['name' => 'Spanish', 'category' => 'Language'],
            ['name' => 'French', 'category' => 'Language'],
            ['name' => 'German', 'category' => 'Language'],
            ['name' => 'Mandarin Chinese', 'category' => 'Language'],
            ['name' => 'Japanese', 'category' => 'Language'],
            ['name' => 'Korean', 'category' => 'Language'],
            ['name' => 'Arabic', 'category' => 'Language'],
            ['name' => 'Russian', 'category' => 'Language'],
            ['name' => 'Portuguese', 'category' => 'Language'],
            ['name' => 'Italian', 'category' => 'Language'],
            ['name' => 'Hindi', 'category' => 'Language'],

            // Engineering (Non-Software)
            ['name' => 'Mechanical Engineering', 'category' => 'Engineering'],
            ['name' => 'Electrical Engineering', 'category' => 'Engineering'],
            ['name' => 'Civil Engineering', 'category' => 'Engineering'],
            ['name' => 'Chemical Engineering', 'category' => 'Engineering'],
            ['name' => 'Aerospace Engineering', 'category' => 'Engineering'],
            ['name' => 'Biomedical Engineering', 'category' => 'Engineering'],
            ['name' => 'Industrial Engineering', 'category' => 'Engineering'],
            ['name' => 'Environmental Engineering', 'category' => 'Engineering'],
            ['name' => 'Materials Science', 'category' => 'Engineering'],
            ['name' => 'CAD/CAM', 'category' => 'Engineering'],

            // Science & Research
            ['name' => 'Biology', 'category' => 'Science & Research'],
            ['name' => 'Chemistry', 'category' => 'Science & Research'],
            ['name' => 'Physics', 'category' => 'Science & Research'],
            ['name' => 'Mathematics', 'category' => 'Science & Research'],
            ['name' => 'Statistics', 'category' => 'Science & Research'],
            ['name' => 'Astronomy', 'category' => 'Science & Research'],
            ['name' => 'Geology', 'category' => 'Science & Research'],
            ['name' => 'Environmental Science', 'category' => 'Science & Research'],
            ['name' => 'Laboratory Techniques', 'category' => 'Science & Research'],
            ['name' => 'Scientific Writing', 'category' => 'Science & Research'],

            // Trades & Manufacturing
            ['name' => 'Welding', 'category' => 'Trades & Manufacturing'],
            ['name' => 'Machining', 'category' => 'Trades & Manufacturing'],
            ['name' => 'Electrical Installation', 'category' => 'Trades & Manufacturing'],
            ['name' => 'Plumbing', 'category' => 'Trades & Manufacturing'],
            ['name' => 'Carpentry', 'category' => 'Trades & Manufacturing'],
            ['name' => 'HVAC Systems', 'category' => 'Trades & Manufacturing'],
            ['name' => 'Electronics Repair', 'category' => 'Trades & Manufacturing'],
            ['name' => 'Automotive Repair', 'category' => 'Trades & Manufacturing'],
            ['name' => 'CNC Machining', 'category' => 'Trades & Manufacturing'],
            ['name' => 'Lean Manufacturing', 'category' => 'Trades & Manufacturing'],
        ];
        
        foreach ($skills as $skill) {
            SkillModel::create([
                'name' => $skill['name'],
                'category' => $skill['category'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
} 