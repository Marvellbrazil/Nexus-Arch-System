--
-- PostgreSQL database dump
--

-- Dumped from database version 18.1
-- Dumped by pg_dump version 18.1

-- Started on 2026-01-09 17:53:01

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 230 (class 1259 OID 49960)
-- Name: categories; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.categories (
    category_id integer NOT NULL,
    category_name character varying(50) NOT NULL
);


ALTER TABLE public.categories OWNER TO postgres;

--
-- TOC entry 229 (class 1259 OID 49959)
-- Name: categories_category_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.categories_category_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.categories_category_id_seq OWNER TO postgres;

--
-- TOC entry 5092 (class 0 OID 0)
-- Dependencies: 229
-- Name: categories_category_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.categories_category_id_seq OWNED BY public.categories.category_id;


--
-- TOC entry 232 (class 1259 OID 49969)
-- Name: category_department_mapping; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.category_department_mapping (
    mapping_id integer NOT NULL,
    category_id integer NOT NULL,
    department_id integer NOT NULL
);


ALTER TABLE public.category_department_mapping OWNER TO postgres;

--
-- TOC entry 231 (class 1259 OID 49968)
-- Name: category_department_mapping_mapping_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.category_department_mapping_mapping_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.category_department_mapping_mapping_id_seq OWNER TO postgres;

--
-- TOC entry 5093 (class 0 OID 0)
-- Dependencies: 231
-- Name: category_department_mapping_mapping_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.category_department_mapping_mapping_id_seq OWNED BY public.category_department_mapping.mapping_id;


--
-- TOC entry 220 (class 1259 OID 49868)
-- Name: departments; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.departments (
    department_id integer NOT NULL,
    department_name character varying(50) NOT NULL,
    description text,
    created_at timestamp without time zone DEFAULT now()
);


ALTER TABLE public.departments OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 49867)
-- Name: departments_department_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.departments_department_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.departments_department_id_seq OWNER TO postgres;

--
-- TOC entry 5094 (class 0 OID 0)
-- Dependencies: 219
-- Name: departments_department_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.departments_department_id_seq OWNED BY public.departments.department_id;


--
-- TOC entry 246 (class 1259 OID 50146)
-- Name: notifications; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.notifications (
    notification_id integer NOT NULL,
    user_id integer NOT NULL,
    ticket_id integer,
    title character varying(255) NOT NULL,
    message text NOT NULL,
    is_read boolean DEFAULT false,
    notification_type character varying(50),
    created_at timestamp without time zone DEFAULT now()
);


ALTER TABLE public.notifications OWNER TO postgres;

--
-- TOC entry 245 (class 1259 OID 50145)
-- Name: notifications_notification_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.notifications_notification_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.notifications_notification_id_seq OWNER TO postgres;

--
-- TOC entry 5095 (class 0 OID 0)
-- Dependencies: 245
-- Name: notifications_notification_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.notifications_notification_id_seq OWNED BY public.notifications.notification_id;


--
-- TOC entry 234 (class 1259 OID 49989)
-- Name: priorities; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.priorities (
    priority_id integer NOT NULL,
    priority_name character varying(20) NOT NULL
);


ALTER TABLE public.priorities OWNER TO postgres;

--
-- TOC entry 233 (class 1259 OID 49988)
-- Name: priorities_priority_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.priorities_priority_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.priorities_priority_id_seq OWNER TO postgres;

--
-- TOC entry 5096 (class 0 OID 0)
-- Dependencies: 233
-- Name: priorities_priority_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.priorities_priority_id_seq OWNED BY public.priorities.priority_id;


--
-- TOC entry 228 (class 1259 OID 49939)
-- Name: project_assignments; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.project_assignments (
    assignment_id integer NOT NULL,
    user_id integer NOT NULL,
    project_id integer NOT NULL,
    assigned_at timestamp without time zone DEFAULT now()
);


ALTER TABLE public.project_assignments OWNER TO postgres;

--
-- TOC entry 227 (class 1259 OID 49938)
-- Name: project_assignments_assignment_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.project_assignments_assignment_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.project_assignments_assignment_id_seq OWNER TO postgres;

--
-- TOC entry 5097 (class 0 OID 0)
-- Dependencies: 227
-- Name: project_assignments_assignment_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.project_assignments_assignment_id_seq OWNED BY public.project_assignments.assignment_id;


--
-- TOC entry 226 (class 1259 OID 49923)
-- Name: projects; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.projects (
    project_id integer NOT NULL,
    project_code character varying(20) NOT NULL,
    project_name character varying(100) NOT NULL,
    description text,
    is_active boolean DEFAULT true,
    created_at timestamp without time zone DEFAULT now()
);


ALTER TABLE public.projects OWNER TO postgres;

--
-- TOC entry 225 (class 1259 OID 49922)
-- Name: projects_project_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.projects_project_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.projects_project_id_seq OWNER TO postgres;

--
-- TOC entry 5098 (class 0 OID 0)
-- Dependencies: 225
-- Name: projects_project_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.projects_project_id_seq OWNED BY public.projects.project_id;


--
-- TOC entry 222 (class 1259 OID 49880)
-- Name: roles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.roles (
    role_id integer NOT NULL,
    role_name character varying(50) NOT NULL,
    description text,
    created_at timestamp without time zone DEFAULT now()
);


ALTER TABLE public.roles OWNER TO postgres;

--
-- TOC entry 221 (class 1259 OID 49879)
-- Name: roles_role_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.roles_role_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.roles_role_id_seq OWNER TO postgres;

--
-- TOC entry 5099 (class 0 OID 0)
-- Dependencies: 221
-- Name: roles_role_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.roles_role_id_seq OWNED BY public.roles.role_id;


--
-- TOC entry 236 (class 1259 OID 49998)
-- Name: statuses; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.statuses (
    status_id integer NOT NULL,
    status_name character varying(20) NOT NULL
);


ALTER TABLE public.statuses OWNER TO postgres;

--
-- TOC entry 235 (class 1259 OID 49997)
-- Name: statuses_status_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.statuses_status_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.statuses_status_id_seq OWNER TO postgres;

--
-- TOC entry 5100 (class 0 OID 0)
-- Dependencies: 235
-- Name: statuses_status_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.statuses_status_id_seq OWNED BY public.statuses.status_id;


--
-- TOC entry 242 (class 1259 OID 50088)
-- Name: ticket_attachments; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ticket_attachments (
    attachment_id integer NOT NULL,
    ticket_id integer NOT NULL,
    uploaded_by integer NOT NULL,
    file_name character varying(255) NOT NULL,
    file_path text NOT NULL,
    file_type character varying(50),
    file_size integer,
    created_at timestamp without time zone DEFAULT now()
);


ALTER TABLE public.ticket_attachments OWNER TO postgres;

--
-- TOC entry 241 (class 1259 OID 50087)
-- Name: ticket_attachments_attachment_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ticket_attachments_attachment_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ticket_attachments_attachment_id_seq OWNER TO postgres;

--
-- TOC entry 5101 (class 0 OID 0)
-- Dependencies: 241
-- Name: ticket_attachments_attachment_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ticket_attachments_attachment_id_seq OWNED BY public.ticket_attachments.attachment_id;


--
-- TOC entry 240 (class 1259 OID 50064)
-- Name: ticket_messages; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ticket_messages (
    message_id integer NOT NULL,
    ticket_id integer NOT NULL,
    sender_id integer NOT NULL,
    message text NOT NULL,
    created_at timestamp without time zone DEFAULT now()
);


ALTER TABLE public.ticket_messages OWNER TO postgres;

--
-- TOC entry 239 (class 1259 OID 50063)
-- Name: ticket_messages_message_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ticket_messages_message_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ticket_messages_message_id_seq OWNER TO postgres;

--
-- TOC entry 5102 (class 0 OID 0)
-- Dependencies: 239
-- Name: ticket_messages_message_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ticket_messages_message_id_seq OWNED BY public.ticket_messages.message_id;


--
-- TOC entry 244 (class 1259 OID 50113)
-- Name: ticket_status_logs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ticket_status_logs (
    log_id integer NOT NULL,
    ticket_id integer NOT NULL,
    changed_by integer NOT NULL,
    old_status_id integer,
    new_status_id integer,
    notes text,
    changed_at timestamp without time zone DEFAULT now()
);


ALTER TABLE public.ticket_status_logs OWNER TO postgres;

--
-- TOC entry 243 (class 1259 OID 50112)
-- Name: ticket_status_logs_log_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ticket_status_logs_log_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ticket_status_logs_log_id_seq OWNER TO postgres;

--
-- TOC entry 5103 (class 0 OID 0)
-- Dependencies: 243
-- Name: ticket_status_logs_log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ticket_status_logs_log_id_seq OWNED BY public.ticket_status_logs.log_id;


--
-- TOC entry 238 (class 1259 OID 50007)
-- Name: tickets; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tickets (
    ticket_id integer NOT NULL,
    ticket_number character varying(20) NOT NULL,
    subject character varying(255) NOT NULL,
    description text NOT NULL,
    customer_id integer NOT NULL,
    project_id integer NOT NULL,
    department_id integer,
    assigned_to integer,
    category_id integer NOT NULL,
    priority_id integer NOT NULL,
    status_id integer NOT NULL,
    due_date timestamp without time zone,
    first_response_at timestamp without time zone,
    resolved_at timestamp without time zone,
    closed_at timestamp without time zone,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone DEFAULT now()
);


ALTER TABLE public.tickets OWNER TO postgres;

--
-- TOC entry 237 (class 1259 OID 50006)
-- Name: tickets_ticket_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tickets_ticket_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tickets_ticket_id_seq OWNER TO postgres;

--
-- TOC entry 5104 (class 0 OID 0)
-- Dependencies: 237
-- Name: tickets_ticket_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tickets_ticket_id_seq OWNED BY public.tickets.ticket_id;


--
-- TOC entry 224 (class 1259 OID 49892)
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    user_id integer NOT NULL,
    username character varying(50) NOT NULL,
    full_name character varying(100) NOT NULL,
    email character varying(100) NOT NULL,
    password character varying(255) NOT NULL,
    role_id integer NOT NULL,
    department_id integer,
    phone_number character varying(20),
    photo_profile text,
    is_active boolean DEFAULT false,
    last_login timestamp without time zone,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.users OWNER TO postgres;

--
-- TOC entry 223 (class 1259 OID 49891)
-- Name: users_user_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_user_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_user_id_seq OWNER TO postgres;

--
-- TOC entry 5105 (class 0 OID 0)
-- Dependencies: 223
-- Name: users_user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_user_id_seq OWNED BY public.users.user_id;


--
-- TOC entry 4833 (class 2604 OID 49963)
-- Name: categories category_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categories ALTER COLUMN category_id SET DEFAULT nextval('public.categories_category_id_seq'::regclass);


--
-- TOC entry 4834 (class 2604 OID 49972)
-- Name: category_department_mapping mapping_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.category_department_mapping ALTER COLUMN mapping_id SET DEFAULT nextval('public.category_department_mapping_mapping_id_seq'::regclass);


--
-- TOC entry 4820 (class 2604 OID 49871)
-- Name: departments department_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.departments ALTER COLUMN department_id SET DEFAULT nextval('public.departments_department_id_seq'::regclass);


--
-- TOC entry 4846 (class 2604 OID 50149)
-- Name: notifications notification_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notifications ALTER COLUMN notification_id SET DEFAULT nextval('public.notifications_notification_id_seq'::regclass);


--
-- TOC entry 4835 (class 2604 OID 49992)
-- Name: priorities priority_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.priorities ALTER COLUMN priority_id SET DEFAULT nextval('public.priorities_priority_id_seq'::regclass);


--
-- TOC entry 4831 (class 2604 OID 49942)
-- Name: project_assignments assignment_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.project_assignments ALTER COLUMN assignment_id SET DEFAULT nextval('public.project_assignments_assignment_id_seq'::regclass);


--
-- TOC entry 4828 (class 2604 OID 49926)
-- Name: projects project_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.projects ALTER COLUMN project_id SET DEFAULT nextval('public.projects_project_id_seq'::regclass);


--
-- TOC entry 4822 (class 2604 OID 49883)
-- Name: roles role_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles ALTER COLUMN role_id SET DEFAULT nextval('public.roles_role_id_seq'::regclass);


--
-- TOC entry 4836 (class 2604 OID 50001)
-- Name: statuses status_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.statuses ALTER COLUMN status_id SET DEFAULT nextval('public.statuses_status_id_seq'::regclass);


--
-- TOC entry 4842 (class 2604 OID 50091)
-- Name: ticket_attachments attachment_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ticket_attachments ALTER COLUMN attachment_id SET DEFAULT nextval('public.ticket_attachments_attachment_id_seq'::regclass);


--
-- TOC entry 4840 (class 2604 OID 50067)
-- Name: ticket_messages message_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ticket_messages ALTER COLUMN message_id SET DEFAULT nextval('public.ticket_messages_message_id_seq'::regclass);


--
-- TOC entry 4844 (class 2604 OID 50116)
-- Name: ticket_status_logs log_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ticket_status_logs ALTER COLUMN log_id SET DEFAULT nextval('public.ticket_status_logs_log_id_seq'::regclass);


--
-- TOC entry 4837 (class 2604 OID 50010)
-- Name: tickets ticket_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tickets ALTER COLUMN ticket_id SET DEFAULT nextval('public.tickets_ticket_id_seq'::regclass);


--
-- TOC entry 4824 (class 2604 OID 49895)
-- Name: users user_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN user_id SET DEFAULT nextval('public.users_user_id_seq'::regclass);


--
-- TOC entry 5070 (class 0 OID 49960)
-- Dependencies: 230
-- Data for Name: categories; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.categories VALUES (1, 'Hardware Issue');
INSERT INTO public.categories VALUES (2, 'Software Issue');
INSERT INTO public.categories VALUES (3, 'Network Problem');
INSERT INTO public.categories VALUES (4, 'Feature Request');
INSERT INTO public.categories VALUES (5, 'Bug Report');
INSERT INTO public.categories VALUES (6, 'UI/UX Issue');


--
-- TOC entry 5072 (class 0 OID 49969)
-- Dependencies: 232
-- Data for Name: category_department_mapping; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.category_department_mapping VALUES (1, 1, 1);
INSERT INTO public.category_department_mapping VALUES (2, 2, 1);
INSERT INTO public.category_department_mapping VALUES (3, 3, 1);
INSERT INTO public.category_department_mapping VALUES (4, 4, 4);
INSERT INTO public.category_department_mapping VALUES (5, 5, 1);
INSERT INTO public.category_department_mapping VALUES (6, 6, 2);


--
-- TOC entry 5060 (class 0 OID 49868)
-- Dependencies: 220
-- Data for Name: departments; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.departments VALUES (1, 'IT Support', 'Technical support for IT infrastructure', '2026-01-09 10:27:16.980932');
INSERT INTO public.departments VALUES (2, 'UI/UX Support', 'User interface and user experience support', '2026-01-09 10:27:16.980932');
INSERT INTO public.departments VALUES (3, 'Technical Support', 'General technical assistance', '2026-01-09 10:27:16.980932');
INSERT INTO public.departments VALUES (4, 'Feature Request', 'Feature development and enhancement requests', '2026-01-09 10:27:16.980932');


--
-- TOC entry 5086 (class 0 OID 50146)
-- Dependencies: 246
-- Data for Name: notifications; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 5074 (class 0 OID 49989)
-- Dependencies: 234
-- Data for Name: priorities; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.priorities VALUES (1, 'Low');
INSERT INTO public.priorities VALUES (2, 'Medium');
INSERT INTO public.priorities VALUES (3, 'High');
INSERT INTO public.priorities VALUES (4, 'Critical');


--
-- TOC entry 5068 (class 0 OID 49939)
-- Dependencies: 228
-- Data for Name: project_assignments; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 5066 (class 0 OID 49923)
-- Dependencies: 226
-- Data for Name: projects; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.projects VALUES (1, 'PROJ001', 'NEXUS System', 'Main NEXUS support system', true, '2026-01-09 10:27:16.980932');
INSERT INTO public.projects VALUES (2, 'PROJ002', 'Mobile App', 'Mobile application support', true, '2026-01-09 10:27:16.980932');
INSERT INTO public.projects VALUES (3, 'PROJ003', 'Web Portal', 'Web portal development', true, '2026-01-09 10:27:16.980932');


--
-- TOC entry 5062 (class 0 OID 49880)
-- Dependencies: 222
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.roles VALUES (1, 'Admin', 'Full system access and management', '2026-01-09 10:27:16.980932');
INSERT INTO public.roles VALUES (2, 'Customer', 'External users who submit tickets', '2026-01-09 10:27:16.980932');
INSERT INTO public.roles VALUES (3, 'Support', 'Internal support team members', '2026-01-09 10:27:16.980932');
INSERT INTO public.roles VALUES (4, 'Department', 'Department-specific support staff', '2026-01-09 10:27:16.980932');


--
-- TOC entry 5076 (class 0 OID 49998)
-- Dependencies: 236
-- Data for Name: statuses; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.statuses VALUES (1, 'Open');
INSERT INTO public.statuses VALUES (2, 'In Progress');
INSERT INTO public.statuses VALUES (3, 'Resolved');
INSERT INTO public.statuses VALUES (4, 'Closed');


--
-- TOC entry 5082 (class 0 OID 50088)
-- Dependencies: 242
-- Data for Name: ticket_attachments; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 5080 (class 0 OID 50064)
-- Dependencies: 240
-- Data for Name: ticket_messages; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 5084 (class 0 OID 50113)
-- Dependencies: 244
-- Data for Name: ticket_status_logs; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 5078 (class 0 OID 50007)
-- Dependencies: 238
-- Data for Name: tickets; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 5064 (class 0 OID 49892)
-- Dependencies: 224
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.users VALUES (5, 'uiux1', 'UI/UX Designer', 'uiux@nexus.com', '$2y$10$u48zXFLN9t1xBOyv4LPE3OKU7L6xlvJDJn11vliBk42fmXibDPPxu', 4, 2, NULL, NULL, true, NULL, '2026-01-09 10:27:16.980932', '2026-01-09 13:02:48.680289');
INSERT INTO public.users VALUES (6, 'techsupport1', 'Technical Support', 'techsupport@nexus.com', '$2y$10$u48zXFLN9t1xBOyv4LPE3OKU7L6xlvJDJn11vliBk42fmXibDPPxu', 4, 3, NULL, NULL, true, NULL, '2026-01-09 10:27:16.980932', '2026-01-09 13:02:48.680289');
INSERT INTO public.users VALUES (7, 'feature1', 'Feature Developer', 'feature@nexus.com', '$2y$10$u48zXFLN9t1xBOyv4LPE3OKU7L6xlvJDJn11vliBk42fmXibDPPxu', 4, 4, NULL, NULL, true, NULL, '2026-01-09 10:27:16.980932', '2026-01-09 13:02:48.680289');
INSERT INTO public.users VALUES (2, 'customer1', 'John Customer', 'customer@nexus.com', '$2y$10$cF2FHo6vY3.suCJxZVfs2eLPeFrT8Bj4Jsf08J1Ebo6LlsjsMtK9q', 2, NULL, NULL, NULL, true, '2026-01-09 09:10:05', '2026-01-09 10:27:16.980932', '2026-01-09 09:10:05');
INSERT INTO public.users VALUES (3, 'support1', 'Jane Support', 'support@nexus.com', '$2y$10$u48zXFLN9t1xBOyv4LPE3OKU7L6xlvJDJn11vliBk42fmXibDPPxu', 3, NULL, NULL, NULL, true, '2026-01-09 09:10:44', '2026-01-09 10:27:16.980932', '2026-01-09 09:10:44');
INSERT INTO public.users VALUES (1, 'admin', 'System Administrator', 'admin@nexus.com', '$2y$10$u48zXFLN9t1xBOyv4LPE3OKU7L6xlvJDJn11vliBk42fmXibDPPxu', 1, NULL, NULL, NULL, true, '2026-01-09 10:22:27', '2026-01-09 10:27:16.980932', '2026-01-09 10:22:27');
INSERT INTO public.users VALUES (4, 'itsupport1', 'IT Support Staff', 'itsupport@nexus.com', '$2y$10$u48zXFLN9t1xBOyv4LPE3OKU7L6xlvJDJn11vliBk42fmXibDPPxu', 4, 1, NULL, NULL, true, '2026-01-09 10:39:43', '2026-01-09 10:27:16.980932', '2026-01-09 10:39:43');


--
-- TOC entry 5106 (class 0 OID 0)
-- Dependencies: 229
-- Name: categories_category_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.categories_category_id_seq', 6, true);


--
-- TOC entry 5107 (class 0 OID 0)
-- Dependencies: 231
-- Name: category_department_mapping_mapping_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.category_department_mapping_mapping_id_seq', 6, true);


--
-- TOC entry 5108 (class 0 OID 0)
-- Dependencies: 219
-- Name: departments_department_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.departments_department_id_seq', 4, true);


--
-- TOC entry 5109 (class 0 OID 0)
-- Dependencies: 245
-- Name: notifications_notification_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.notifications_notification_id_seq', 1, false);


--
-- TOC entry 5110 (class 0 OID 0)
-- Dependencies: 233
-- Name: priorities_priority_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.priorities_priority_id_seq', 4, true);


--
-- TOC entry 5111 (class 0 OID 0)
-- Dependencies: 227
-- Name: project_assignments_assignment_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.project_assignments_assignment_id_seq', 1, false);


--
-- TOC entry 5112 (class 0 OID 0)
-- Dependencies: 225
-- Name: projects_project_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.projects_project_id_seq', 3, true);


--
-- TOC entry 5113 (class 0 OID 0)
-- Dependencies: 221
-- Name: roles_role_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.roles_role_id_seq', 4, true);


--
-- TOC entry 5114 (class 0 OID 0)
-- Dependencies: 235
-- Name: statuses_status_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.statuses_status_id_seq', 4, true);


--
-- TOC entry 5115 (class 0 OID 0)
-- Dependencies: 241
-- Name: ticket_attachments_attachment_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ticket_attachments_attachment_id_seq', 1, false);


--
-- TOC entry 5116 (class 0 OID 0)
-- Dependencies: 239
-- Name: ticket_messages_message_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ticket_messages_message_id_seq', 1, false);


--
-- TOC entry 5117 (class 0 OID 0)
-- Dependencies: 243
-- Name: ticket_status_logs_log_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ticket_status_logs_log_id_seq', 1, false);


--
-- TOC entry 5118 (class 0 OID 0)
-- Dependencies: 237
-- Name: tickets_ticket_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tickets_ticket_id_seq', 1, false);


--
-- TOC entry 5119 (class 0 OID 0)
-- Dependencies: 223
-- Name: users_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_user_id_seq', 7, true);


--
-- TOC entry 4866 (class 2606 OID 49967)
-- Name: categories categories_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_pkey PRIMARY KEY (category_id);


--
-- TOC entry 4868 (class 2606 OID 49977)
-- Name: category_department_mapping category_department_mapping_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.category_department_mapping
    ADD CONSTRAINT category_department_mapping_pkey PRIMARY KEY (mapping_id);


--
-- TOC entry 4850 (class 2606 OID 49878)
-- Name: departments departments_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.departments
    ADD CONSTRAINT departments_pkey PRIMARY KEY (department_id);


--
-- TOC entry 4888 (class 2606 OID 50159)
-- Name: notifications notifications_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notifications
    ADD CONSTRAINT notifications_pkey PRIMARY KEY (notification_id);


--
-- TOC entry 4870 (class 2606 OID 49996)
-- Name: priorities priorities_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.priorities
    ADD CONSTRAINT priorities_pkey PRIMARY KEY (priority_id);


--
-- TOC entry 4864 (class 2606 OID 49948)
-- Name: project_assignments project_assignments_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.project_assignments
    ADD CONSTRAINT project_assignments_pkey PRIMARY KEY (assignment_id);


--
-- TOC entry 4860 (class 2606 OID 49935)
-- Name: projects projects_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.projects
    ADD CONSTRAINT projects_pkey PRIMARY KEY (project_id);


--
-- TOC entry 4862 (class 2606 OID 49937)
-- Name: projects projects_project_code_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.projects
    ADD CONSTRAINT projects_project_code_key UNIQUE (project_code);


--
-- TOC entry 4852 (class 2606 OID 49890)
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (role_id);


--
-- TOC entry 4872 (class 2606 OID 50005)
-- Name: statuses statuses_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.statuses
    ADD CONSTRAINT statuses_pkey PRIMARY KEY (status_id);


--
-- TOC entry 4883 (class 2606 OID 50101)
-- Name: ticket_attachments ticket_attachments_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ticket_attachments
    ADD CONSTRAINT ticket_attachments_pkey PRIMARY KEY (attachment_id);


--
-- TOC entry 4881 (class 2606 OID 50076)
-- Name: ticket_messages ticket_messages_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ticket_messages
    ADD CONSTRAINT ticket_messages_pkey PRIMARY KEY (message_id);


--
-- TOC entry 4885 (class 2606 OID 50124)
-- Name: ticket_status_logs ticket_status_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ticket_status_logs
    ADD CONSTRAINT ticket_status_logs_pkey PRIMARY KEY (log_id);


--
-- TOC entry 4877 (class 2606 OID 50025)
-- Name: tickets tickets_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tickets
    ADD CONSTRAINT tickets_pkey PRIMARY KEY (ticket_id);


--
-- TOC entry 4879 (class 2606 OID 50027)
-- Name: tickets tickets_ticket_number_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tickets
    ADD CONSTRAINT tickets_ticket_number_key UNIQUE (ticket_number);


--
-- TOC entry 4854 (class 2606 OID 49911)
-- Name: users users_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_key UNIQUE (email);


--
-- TOC entry 4856 (class 2606 OID 49907)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (user_id);


--
-- TOC entry 4858 (class 2606 OID 49909)
-- Name: users users_username_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_username_key UNIQUE (username);


--
-- TOC entry 4886 (class 1259 OID 50173)
-- Name: index_notifications_user; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_notifications_user ON public.notifications USING btree (user_id, is_read);


--
-- TOC entry 4873 (class 1259 OID 50170)
-- Name: index_tickets_customer; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_tickets_customer ON public.tickets USING btree (customer_id);


--
-- TOC entry 4874 (class 1259 OID 50171)
-- Name: index_tickets_project; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_tickets_project ON public.tickets USING btree (project_id);


--
-- TOC entry 4875 (class 1259 OID 50172)
-- Name: index_tickets_status; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX index_tickets_status ON public.tickets USING btree (status_id);


--
-- TOC entry 4893 (class 2606 OID 49978)
-- Name: category_department_mapping category_department_mapping_category_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.category_department_mapping
    ADD CONSTRAINT category_department_mapping_category_id_fkey FOREIGN KEY (category_id) REFERENCES public.categories(category_id);


--
-- TOC entry 4894 (class 2606 OID 49983)
-- Name: category_department_mapping category_department_mapping_department_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.category_department_mapping
    ADD CONSTRAINT category_department_mapping_department_id_fkey FOREIGN KEY (department_id) REFERENCES public.departments(department_id);


--
-- TOC entry 4910 (class 2606 OID 50165)
-- Name: notifications notifications_ticket_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notifications
    ADD CONSTRAINT notifications_ticket_id_fkey FOREIGN KEY (ticket_id) REFERENCES public.tickets(ticket_id);


--
-- TOC entry 4911 (class 2606 OID 50160)
-- Name: notifications notifications_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notifications
    ADD CONSTRAINT notifications_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(user_id);


--
-- TOC entry 4891 (class 2606 OID 49954)
-- Name: project_assignments project_assignments_project_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.project_assignments
    ADD CONSTRAINT project_assignments_project_id_fkey FOREIGN KEY (project_id) REFERENCES public.projects(project_id);


--
-- TOC entry 4892 (class 2606 OID 49949)
-- Name: project_assignments project_assignments_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.project_assignments
    ADD CONSTRAINT project_assignments_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(user_id);


--
-- TOC entry 4904 (class 2606 OID 50102)
-- Name: ticket_attachments ticket_attachments_ticket_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ticket_attachments
    ADD CONSTRAINT ticket_attachments_ticket_id_fkey FOREIGN KEY (ticket_id) REFERENCES public.tickets(ticket_id);


--
-- TOC entry 4905 (class 2606 OID 50107)
-- Name: ticket_attachments ticket_attachments_uploaded_by_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ticket_attachments
    ADD CONSTRAINT ticket_attachments_uploaded_by_fkey FOREIGN KEY (uploaded_by) REFERENCES public.users(user_id);


--
-- TOC entry 4902 (class 2606 OID 50082)
-- Name: ticket_messages ticket_messages_sender_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ticket_messages
    ADD CONSTRAINT ticket_messages_sender_id_fkey FOREIGN KEY (sender_id) REFERENCES public.users(user_id);


--
-- TOC entry 4903 (class 2606 OID 50077)
-- Name: ticket_messages ticket_messages_ticket_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ticket_messages
    ADD CONSTRAINT ticket_messages_ticket_id_fkey FOREIGN KEY (ticket_id) REFERENCES public.tickets(ticket_id);


--
-- TOC entry 4906 (class 2606 OID 50130)
-- Name: ticket_status_logs ticket_status_logs_changed_by_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ticket_status_logs
    ADD CONSTRAINT ticket_status_logs_changed_by_fkey FOREIGN KEY (changed_by) REFERENCES public.users(user_id);


--
-- TOC entry 4907 (class 2606 OID 50140)
-- Name: ticket_status_logs ticket_status_logs_new_status_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ticket_status_logs
    ADD CONSTRAINT ticket_status_logs_new_status_id_fkey FOREIGN KEY (new_status_id) REFERENCES public.statuses(status_id);


--
-- TOC entry 4908 (class 2606 OID 50135)
-- Name: ticket_status_logs ticket_status_logs_old_status_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ticket_status_logs
    ADD CONSTRAINT ticket_status_logs_old_status_id_fkey FOREIGN KEY (old_status_id) REFERENCES public.statuses(status_id);


--
-- TOC entry 4909 (class 2606 OID 50125)
-- Name: ticket_status_logs ticket_status_logs_ticket_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ticket_status_logs
    ADD CONSTRAINT ticket_status_logs_ticket_id_fkey FOREIGN KEY (ticket_id) REFERENCES public.tickets(ticket_id);


--
-- TOC entry 4895 (class 2606 OID 50043)
-- Name: tickets tickets_assigned_to_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tickets
    ADD CONSTRAINT tickets_assigned_to_fkey FOREIGN KEY (assigned_to) REFERENCES public.users(user_id);


--
-- TOC entry 4896 (class 2606 OID 50048)
-- Name: tickets tickets_category_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tickets
    ADD CONSTRAINT tickets_category_id_fkey FOREIGN KEY (category_id) REFERENCES public.categories(category_id);


--
-- TOC entry 4897 (class 2606 OID 50028)
-- Name: tickets tickets_customer_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tickets
    ADD CONSTRAINT tickets_customer_id_fkey FOREIGN KEY (customer_id) REFERENCES public.users(user_id);


--
-- TOC entry 4898 (class 2606 OID 50038)
-- Name: tickets tickets_department_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tickets
    ADD CONSTRAINT tickets_department_id_fkey FOREIGN KEY (department_id) REFERENCES public.departments(department_id);


--
-- TOC entry 4899 (class 2606 OID 50053)
-- Name: tickets tickets_priority_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tickets
    ADD CONSTRAINT tickets_priority_id_fkey FOREIGN KEY (priority_id) REFERENCES public.priorities(priority_id);


--
-- TOC entry 4900 (class 2606 OID 50033)
-- Name: tickets tickets_project_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tickets
    ADD CONSTRAINT tickets_project_id_fkey FOREIGN KEY (project_id) REFERENCES public.projects(project_id);


--
-- TOC entry 4901 (class 2606 OID 50058)
-- Name: tickets tickets_status_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tickets
    ADD CONSTRAINT tickets_status_id_fkey FOREIGN KEY (status_id) REFERENCES public.statuses(status_id);


--
-- TOC entry 4889 (class 2606 OID 49917)
-- Name: users users_department_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_department_id_fkey FOREIGN KEY (department_id) REFERENCES public.departments(department_id);


--
-- TOC entry 4890 (class 2606 OID 49912)
-- Name: users users_role_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_role_id_fkey FOREIGN KEY (role_id) REFERENCES public.roles(role_id);


-- Completed on 2026-01-09 17:53:09

--
-- PostgreSQL database dump complete
--