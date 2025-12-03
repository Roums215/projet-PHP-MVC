-- Table: public.user

-- DROP TABLE IF EXISTS public."user";

CREATE TABLE IF NOT EXISTS public."user"
(
    id integer NOT NULL DEFAULT nextval('user_id_seq'::regclass),
    firstname character varying(50) COLLATE pg_catalog."default",
    lastname character varying(100) COLLATE pg_catalog."default",
    email character varying(320) COLLATE pg_catalog."default" NOT NULL,
    pwd character varying(255) COLLATE pg_catalog."default" NOT NULL,
    is_active boolean DEFAULT false,
    date_created date NOT NULL,
    date_updated date,
    CONSTRAINT user_pkey PRIMARY KEY (id)
    )

    TABLESPACE pg_default;

ALTER TABLE IF EXISTS public."user"
    OWNER to devuser;



-- DATABASE
DROP TABLE IF EXISTS password_resets CASCADE;
DROP TABLE IF EXISTS pages CASCADE;
DROP TABLE IF EXISTS users CASCADE;


CREATE TABLE users (
    id            SERIAL PRIMARY KEY,
    firstname     VARCHAR(50),
    lastname      VARCHAR(100),
    email         VARCHAR(320) UNIQUE NOT NULL,
    pwd           VARCHAR(255) NOT NULL,
    is_active     BOOLEAN DEFAULT FALSE,
    created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP
);

ALTER TABLE users OWNER TO devuser;



CREATE TABLE password_resets (

    id          SERIAL PRIMARY KEY,
    user_id     INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    token       VARCHAR(255) NOT NULL,
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE password_resets OWNER TO devuser;


CREATE TABLE pages (
    id          SERIAL PRIMARY KEY,
    title       VARCHAR(255) NOT NULL,
    slug        VARCHAR(255) UNIQUE NOT NULL,
    
    content     TEXT NOT NULL,
    is_published BOOLEAN NOT NULL DEFAULT TRUE,
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP
);

ALTER TABLE pages OWNER TO devuser;
