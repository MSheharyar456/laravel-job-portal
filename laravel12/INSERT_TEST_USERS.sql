-- Insert Test Users for BrainBrick Job Portal
-- Password for all: "password"

-- 1. Admin User
INSERT INTO `users` (`name`, `email`, `password`, `role`, `phone`, `email_verified_at`, `created_at`, `updated_at`) VALUES
('Admin User', 'admin@jobportal.com', '$2y$12$LQv3c1yZLuSjDnZfnZC2ZOMPLqQXxCOtDZPxCxZuFCxZuFCxZuFCx.', 'admin', '+923001234567', NOW(), NOW(), NOW());

-- 2. Moderator User
INSERT INTO `users` (`name`, `email`, `password`, `role`, `phone`, `email_verified_at`, `created_at`, `updated_at`) VALUES
('Moderator User', 'moderator@jobportal.com', '$2y$12$LQv3c1yZLuSjDnZfnZC2ZOMPLqQXxCOtDZPxCxZuFCxZuFCxZuFCx.', 'moderator', '+923001234568', NOW(), NOW(), NOW());

-- 3. Employer User
INSERT INTO `users` (`name`, `email`, `password`, `role`, `phone`, `email_verified_at`, `created_at`, `updated_at`) VALUES
('Tech Company', 'employer1@jobportal.com', '$2y$12$LQv3c1yZLuSjDnZfnZC2ZOMPLqQXxCOtDZPxCxZuFCxZuFCxZuFCx.', 'employer', '+923001234569', NOW(), NOW(), NOW());

-- 4. Job Seeker User
INSERT INTO `users` (`name`, `email`, `password`, `role`, `phone`, `email_verified_at`, `created_at`, `updated_at`) VALUES
('Ali Ahmed', 'jobseeker1@jobportal.com', '$2y$12$LQv3c1yZLuSjDnZfnZC2ZOMPLqQXxCOtDZPxCxZuFCxZuFCxZuFCx.', 'job_seeker', '+923001234570', NOW(), NOW(), NOW());

-- Create Employer Profile (for employer1)
-- Note: Change user_id=3 to match the actual ID from users table
INSERT INTO `employers` (`user_id`, `company_name`, `company_description`, `website`, `created_at`, `updated_at`) VALUES
(3, 'Tech Company', 'Leading IT solutions provider in Pakistan', 'https://techcompany.pk', NOW(), NOW());

-- Create Job Seeker Profile (for jobseeker1)
-- Note: Change user_id=4 to match the actual ID from users table
INSERT INTO `job_seeker_profiles` (`user_id`, `skills`, `experience`, `education`, `created_at`, `updated_at`) VALUES
(4, 'PHP, Laravel, MySQL, JavaScript', '2 years experience in web development', 'BS Computer Science', NOW(), NOW());

-- Add Sample Categories
INSERT INTO `categories` (`name`, `description`, `created_at`, `updated_at`) VALUES
('IT & Software', 'Information Technology and Software Development', NOW(), NOW()),
('Marketing', 'Marketing and Digital Marketing', NOW(), NOW()),
('Sales', 'Sales and Business Development', NOW(), NOW()),
('Design', 'Graphic Design and UI/UX', NOW(), NOW()),
('Finance', 'Accounting and Financial Services', NOW(), NOW());
