DROP TABLE IF EXISTS "public"."categories";
-- Sequence and defined type
CREATE SEQUENCE IF NOT EXISTS categories_category_id_seq;

-- Table Definition
CREATE TABLE "public"."categories" (
    "category_id" int4 NOT NULL DEFAULT nextval('categories_category_id_seq'::regclass),
    "category_name" varchar(50) NOT NULL,
    PRIMARY KEY ("category_id")
);

DROP TABLE IF EXISTS "public"."category_department_mapping";
-- Sequence and defined type
CREATE SEQUENCE IF NOT EXISTS category_department_mapping_mapping_id_seq;

-- Table Definition
CREATE TABLE "public"."category_department_mapping" (
    "mapping_id" int4 NOT NULL DEFAULT nextval('category_department_mapping_mapping_id_seq'::regclass),
    "category_id" int4 NOT NULL,
    "department_id" int4 NOT NULL,
    CONSTRAINT "category_department_mapping_category_id_fkey" FOREIGN KEY ("category_id") REFERENCES "public"."categories"("category_id"),
    CONSTRAINT "category_department_mapping_department_id_fkey" FOREIGN KEY ("department_id") REFERENCES "public"."departments"("department_id"),
    PRIMARY KEY ("mapping_id")
);

DROP TABLE IF EXISTS "public"."departments";
-- Sequence and defined type
CREATE SEQUENCE IF NOT EXISTS departments_department_id_seq;

-- Table Definition
CREATE TABLE "public"."departments" (
    "department_id" int4 NOT NULL DEFAULT nextval('departments_department_id_seq'::regclass),
    "department_name" varchar(50) NOT NULL,
    "description" text,
    "created_at" timestamp DEFAULT now(),
    PRIMARY KEY ("department_id")
);

DROP TABLE IF EXISTS "public"."notifications";
-- Sequence and defined type
CREATE SEQUENCE IF NOT EXISTS notifications_notification_id_seq;

-- Table Definition
CREATE TABLE "public"."notifications" (
    "notification_id" int4 NOT NULL DEFAULT nextval('notifications_notification_id_seq'::regclass),
    "user_id" int4 NOT NULL,
    "ticket_id" int4,
    "title" varchar(255) NOT NULL,
    "message" text NOT NULL,
    "is_read" bool DEFAULT false,
    "notification_type" varchar(50),
    "created_at" timestamp DEFAULT now(),
    CONSTRAINT "notifications_ticket_id_fkey" FOREIGN KEY ("ticket_id") REFERENCES "public"."tickets"("ticket_id"),
    CONSTRAINT "notifications_user_id_fkey" FOREIGN KEY ("user_id") REFERENCES "public"."users"("user_id"),
    PRIMARY KEY ("notification_id")
);


-- Indices
CREATE INDEX index_notifications_user ON public.notifications USING btree (user_id, is_read);

DROP TABLE IF EXISTS "public"."priorities";
-- Sequence and defined type
CREATE SEQUENCE IF NOT EXISTS priorities_priority_id_seq;

-- Table Definition
CREATE TABLE "public"."priorities" (
    "priority_id" int4 NOT NULL DEFAULT nextval('priorities_priority_id_seq'::regclass),
    "priority_name" varchar(20) NOT NULL,
    PRIMARY KEY ("priority_id")
);

DROP TABLE IF EXISTS "public"."project_assignments";
-- Sequence and defined type
CREATE SEQUENCE IF NOT EXISTS project_assignments_assignment_id_seq;

-- Table Definition
CREATE TABLE "public"."project_assignments" (
    "assignment_id" int4 NOT NULL DEFAULT nextval('project_assignments_assignment_id_seq'::regclass),
    "user_id" int4 NOT NULL,
    "project_id" int4 NOT NULL,
    "assigned_at" timestamp DEFAULT now(),
    CONSTRAINT "project_assignments_project_id_fkey" FOREIGN KEY ("project_id") REFERENCES "public"."projects"("project_id"),
    CONSTRAINT "project_assignments_user_id_fkey" FOREIGN KEY ("user_id") REFERENCES "public"."users"("user_id"),
    PRIMARY KEY ("assignment_id")
);

DROP TABLE IF EXISTS "public"."projects";
-- Sequence and defined type
CREATE SEQUENCE IF NOT EXISTS projects_project_id_seq;

-- Table Definition
CREATE TABLE "public"."projects" (
    "project_id" int4 NOT NULL DEFAULT nextval('projects_project_id_seq'::regclass),
    "project_code" varchar(20) NOT NULL,
    "project_name" varchar(100) NOT NULL,
    "description" text,
    "is_active" bool DEFAULT true,
    "created_at" timestamp DEFAULT now(),
    PRIMARY KEY ("project_id")
);


-- Indices
CREATE UNIQUE INDEX projects_project_code_key ON public.projects USING btree (project_code);

DROP TABLE IF EXISTS "public"."roles";
-- Sequence and defined type
CREATE SEQUENCE IF NOT EXISTS roles_role_id_seq;

-- Table Definition
CREATE TABLE "public"."roles" (
    "role_id" int4 NOT NULL DEFAULT nextval('roles_role_id_seq'::regclass),
    "role_name" varchar(50) NOT NULL,
    "description" text,
    "created_at" timestamp DEFAULT now(),
    PRIMARY KEY ("role_id")
);

DROP TABLE IF EXISTS "public"."statuses";
-- Sequence and defined type
CREATE SEQUENCE IF NOT EXISTS statuses_status_id_seq;

-- Table Definition
CREATE TABLE "public"."statuses" (
    "status_id" int4 NOT NULL DEFAULT nextval('statuses_status_id_seq'::regclass),
    "status_name" varchar(20) NOT NULL,
    PRIMARY KEY ("status_id")
);

DROP TABLE IF EXISTS "public"."ticket_attachments";
-- Sequence and defined type
CREATE SEQUENCE IF NOT EXISTS ticket_attachments_attachment_id_seq;

-- Table Definition
CREATE TABLE "public"."ticket_attachments" (
    "attachment_id" int4 NOT NULL DEFAULT nextval('ticket_attachments_attachment_id_seq'::regclass),
    "ticket_id" int4 NOT NULL,
    "uploaded_by" int4 NOT NULL,
    "file_name" varchar(255) NOT NULL,
    "file_path" text NOT NULL,
    "file_type" varchar(50),
    "file_size" int4,
    "created_at" timestamp DEFAULT now(),
    CONSTRAINT "ticket_attachments_ticket_id_fkey" FOREIGN KEY ("ticket_id") REFERENCES "public"."tickets"("ticket_id"),
    CONSTRAINT "ticket_attachments_uploaded_by_fkey" FOREIGN KEY ("uploaded_by") REFERENCES "public"."users"("user_id"),
    PRIMARY KEY ("attachment_id")
);

DROP TABLE IF EXISTS "public"."ticket_messages";
-- Sequence and defined type
CREATE SEQUENCE IF NOT EXISTS ticket_messages_message_id_seq;

-- Table Definition
CREATE TABLE "public"."ticket_messages" (
    "message_id" int4 NOT NULL DEFAULT nextval('ticket_messages_message_id_seq'::regclass),
    "ticket_id" int4 NOT NULL,
    "sender_id" int4 NOT NULL,
    "message" text NOT NULL,
    "created_at" timestamp DEFAULT now(),
    CONSTRAINT "ticket_messages_sender_id_fkey" FOREIGN KEY ("sender_id") REFERENCES "public"."users"("user_id"),
    CONSTRAINT "ticket_messages_ticket_id_fkey" FOREIGN KEY ("ticket_id") REFERENCES "public"."tickets"("ticket_id"),
    PRIMARY KEY ("message_id")
);

DROP TABLE IF EXISTS "public"."ticket_status_logs";
-- Sequence and defined type
CREATE SEQUENCE IF NOT EXISTS ticket_status_logs_log_id_seq;

-- Table Definition
CREATE TABLE "public"."ticket_status_logs" (
    "log_id" int4 NOT NULL DEFAULT nextval('ticket_status_logs_log_id_seq'::regclass),
    "ticket_id" int4 NOT NULL,
    "changed_by" int4 NOT NULL,
    "old_status_id" int4,
    "new_status_id" int4,
    "notes" text,
    "changed_at" timestamp DEFAULT now(),
    CONSTRAINT "ticket_status_logs_changed_by_fkey" FOREIGN KEY ("changed_by") REFERENCES "public"."users"("user_id"),
    CONSTRAINT "ticket_status_logs_new_status_id_fkey" FOREIGN KEY ("new_status_id") REFERENCES "public"."statuses"("status_id"),
    CONSTRAINT "ticket_status_logs_old_status_id_fkey" FOREIGN KEY ("old_status_id") REFERENCES "public"."statuses"("status_id"),
    CONSTRAINT "ticket_status_logs_ticket_id_fkey" FOREIGN KEY ("ticket_id") REFERENCES "public"."tickets"("ticket_id"),
    PRIMARY KEY ("log_id")
);

DROP TABLE IF EXISTS "public"."tickets";
-- Sequence and defined type
CREATE SEQUENCE IF NOT EXISTS tickets_ticket_id_seq;

-- Table Definition
CREATE TABLE "public"."tickets" (
    "ticket_id" int4 NOT NULL DEFAULT nextval('tickets_ticket_id_seq'::regclass),
    "ticket_number" varchar(20) NOT NULL,
    "subject" varchar(255) NOT NULL,
    "description" text NOT NULL,
    "customer_id" int4 NOT NULL,
    "project_id" int4 NOT NULL,
    "department_id" int4,
    "assigned_to" int4,
    "category_id" int4 NOT NULL,
    "priority_id" int4 NOT NULL,
    "status_id" int4 NOT NULL,
    "due_date" timestamp,
    "first_response_at" timestamp,
    "resolved_at" timestamp,
    "closed_at" timestamp,
    "created_at" timestamp DEFAULT now(),
    "updated_at" timestamp DEFAULT now(),
    CONSTRAINT "tickets_assigned_to_fkey" FOREIGN KEY ("assigned_to") REFERENCES "public"."users"("user_id"),
    CONSTRAINT "tickets_category_id_fkey" FOREIGN KEY ("category_id") REFERENCES "public"."categories"("category_id"),
    CONSTRAINT "tickets_customer_id_fkey" FOREIGN KEY ("customer_id") REFERENCES "public"."users"("user_id"),
    CONSTRAINT "tickets_department_id_fkey" FOREIGN KEY ("department_id") REFERENCES "public"."departments"("department_id"),
    CONSTRAINT "tickets_priority_id_fkey" FOREIGN KEY ("priority_id") REFERENCES "public"."priorities"("priority_id"),
    CONSTRAINT "tickets_project_id_fkey" FOREIGN KEY ("project_id") REFERENCES "public"."projects"("project_id"),
    CONSTRAINT "tickets_status_id_fkey" FOREIGN KEY ("status_id") REFERENCES "public"."statuses"("status_id"),
    PRIMARY KEY ("ticket_id")
);


-- Indices
CREATE UNIQUE INDEX tickets_ticket_number_key ON public.tickets USING btree (ticket_number);
CREATE INDEX index_tickets_customer ON public.tickets USING btree (customer_id);
CREATE INDEX index_tickets_project ON public.tickets USING btree (project_id);
CREATE INDEX index_tickets_status ON public.tickets USING btree (status_id);

DROP TABLE IF EXISTS "public"."users";
-- Sequence and defined type
CREATE SEQUENCE IF NOT EXISTS users_user_id_seq;

-- Table Definition
CREATE TABLE "public"."users" (
    "user_id" int4 NOT NULL DEFAULT nextval('users_user_id_seq'::regclass),
    "username" varchar(50) NOT NULL,
    "full_name" varchar(100) NOT NULL,
    "email" varchar(100) NOT NULL,
    "password" varchar(255) NOT NULL,
    "role_id" int4 NOT NULL,
    "department_id" int4,
    "phone_number" varchar(20),
    "photo_profile" text,
    "is_active" bool DEFAULT false,
    "last_login" timestamp,
    "created_at" timestamp DEFAULT now(),
    "updated_at" timestamp DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT "users_department_id_fkey" FOREIGN KEY ("department_id") REFERENCES "public"."departments"("department_id"),
    CONSTRAINT "users_role_id_fkey" FOREIGN KEY ("role_id") REFERENCES "public"."roles"("role_id"),
    PRIMARY KEY ("user_id")
);


-- Indices
CREATE UNIQUE INDEX users_email_key ON public.users USING btree (email);
CREATE UNIQUE INDEX users_username_key ON public.users USING btree (username);

INSERT INTO "public"."categories" ("category_id", "category_name") VALUES
(1, 'Hardware Issue'),
(2, 'Software Issue'),
(3, 'Network Problem'),
(4, 'Feature Request'),
(5, 'Bug Report'),
(6, 'UI/UX Issue');
INSERT INTO "public"."category_department_mapping" ("mapping_id", "category_id", "department_id") VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1),
(4, 4, 4),
(5, 5, 1),
(6, 6, 2);
INSERT INTO "public"."departments" ("department_id", "department_name", "description", "created_at") VALUES
(1, 'IT Support', 'Technical support for IT infrastructure', '2026-01-09 10:27:16.980932'),
(2, 'UI/UX Support', 'User interface and user experience support', '2026-01-09 10:27:16.980932'),
(3, 'Technical Support', 'General technical assistance', '2026-01-09 10:27:16.980932'),
(4, 'Feature Request', 'Feature development and enhancement requests', '2026-01-09 10:27:16.980932');

INSERT INTO "public"."priorities" ("priority_id", "priority_name") VALUES
(1, 'Low'),
(2, 'Medium'),
(3, 'High'),
(4, 'Critical');

INSERT INTO "public"."projects" ("project_id", "project_code", "project_name", "description", "is_active", "created_at") VALUES
(1, 'PROJ001', 'NEXUS System', 'Main NEXUS support system', 't', '2026-01-09 10:27:16.980932'),
(2, 'PROJ002', 'Mobile App', 'Mobile application support', 't', '2026-01-09 10:27:16.980932'),
(3, 'PROJ003', 'Web Portal', 'Web portal development', 't', '2026-01-09 10:27:16.980932');
INSERT INTO "public"."roles" ("role_id", "role_name", "description", "created_at") VALUES
(1, 'Admin', 'Full system access and management', '2026-01-09 10:27:16.980932'),
(2, 'Customer', 'External users who submit tickets', '2026-01-09 10:27:16.980932'),
(3, 'Support', 'Internal support team members', '2026-01-09 10:27:16.980932'),
(4, 'Department', 'Department-specific support staff', '2026-01-09 10:27:16.980932');
INSERT INTO "public"."statuses" ("status_id", "status_name") VALUES
(1, 'Open'),
(2, 'In Progress'),
(3, 'Resolved'),
(4, 'Cancelled');




INSERT INTO "public"."users" ("user_id", "username", "full_name", "email", "password", "role_id", "department_id", "phone_number", "photo_profile", "is_active", "last_login", "created_at", "updated_at") VALUES
(1, 'admin', 'System Administrator', 'admin@nexus.com', '$2y$10$u48zXFLN9t1xBOyv4LPE3OKU7L6xlvJDJn11vliBk42fmXibDPPxu', 1, NULL, NULL, NULL, 't', '2026-01-09 10:22:27', '2026-01-09 10:27:16.980932', '2026-01-09 10:22:27'),
(3, 'support1', 'Jane Support', 'support@nexus.com', '$2y$10$u48zXFLN9t1xBOyv4LPE3OKU7L6xlvJDJn11vliBk42fmXibDPPxu', 3, NULL, NULL, NULL, 't', '2026-01-09 09:10:44', '2026-01-09 10:27:16.980932', '2026-01-09 09:10:44'),
(4, 'itsupport1', 'IT Support Staff', 'itsupport@nexus.com', '$2y$10$u48zXFLN9t1xBOyv4LPE3OKU7L6xlvJDJn11vliBk42fmXibDPPxu', 4, 1, NULL, NULL, 't', '2026-01-09 10:39:43', '2026-01-09 10:27:16.980932', '2026-01-09 10:39:43'),
(5, 'uiux1', 'UI/UX Designer', 'uiux@nexus.com', '$2y$10$u48zXFLN9t1xBOyv4LPE3OKU7L6xlvJDJn11vliBk42fmXibDPPxu', 4, 2, NULL, NULL, 't', NULL, '2026-01-09 10:27:16.980932', '2026-01-09 13:02:48.680289'),
(6, 'techsupport1', 'Technical Support', 'techsupport@nexus.com', '$2y$10$u48zXFLN9t1xBOyv4LPE3OKU7L6xlvJDJn11vliBk42fmXibDPPxu', 4, 3, NULL, NULL, 't', NULL, '2026-01-09 10:27:16.980932', '2026-01-09 13:02:48.680289'),
(7, 'feature1', 'Feature Developer', 'feature@nexus.com', '$2y$10$u48zXFLN9t1xBOyv4LPE3OKU7L6xlvJDJn11vliBk42fmXibDPPxu', 4, 4, NULL, NULL, 't', NULL, '2026-01-09 10:27:16.980932', '2026-01-09 13:02:48.680289'),
(2, 'janedoe', 'Jane Doe', 'janedoe@nexus.com', '$2y$10$cF2FHo6vY3.suCJxZVfs2eLPeFrT8Bj4Jsf08J1Ebo6LlsjsMtK9q', 2, NULL, NULL, NULL, 't', '2026-01-10 19:30:40', '2026-01-09 10:27:16.980932', '2026-01-10 19:30:40'),
(8, 'customer', 'Customer Customer', 'customer@nexus.com', '$2y$10$QPJtsp8ODWD9.Wb3ZSyO9eB2JbB9ICSdOQDDNrJ.SGP7zrLB7oGLG', 2, NULL, 'NULL', 'NULL', 't', NULL, '2026-01-10 21:32:22.414413', '2026-01-10 21:32:22.414413');
