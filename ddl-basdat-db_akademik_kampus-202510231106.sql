--
-- PostgreSQL database cluster dump
--

-- Started on 2025-10-23 11:06:28 WIB

\restrict 4aFL236NRvGfs4fOzztnKgNk3FwRp9Ch1rDcUVAtAUM9XWGTkVXzW2jc8Qnd2L5

SET default_transaction_read_only = off;

SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;


--
-- User Configurations
--








\unrestrict 4aFL236NRvGfs4fOzztnKgNk3FwRp9Ch1rDcUVAtAUM9XWGTkVXzW2jc8Qnd2L5

--
-- Databases
--

--
-- Database "template1" dump
--

\connect template1

--
-- PostgreSQL database dump
--

\restrict bqCceI2qb8Ohsw8oBnLuYaxCeh2T1JWx6kWKxIhdEUtinpva4yHag9b6arp7bAk

-- Dumped from database version 17.6 (Debian 17.6-1.pgdg12+1)
-- Dumped by pg_dump version 18.0

-- Started on 2025-10-23 11:06:28 WIB

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

-- Completed on 2025-10-23 11:06:28 WIB

--
-- PostgreSQL database dump complete
--

\unrestrict bqCceI2qb8Ohsw8oBnLuYaxCeh2T1JWx6kWKxIhdEUtinpva4yHag9b6arp7bAk

--
-- Database "db_akademik_kampus" dump
--

--
-- PostgreSQL database dump
--

\restrict BKvNwkSg6QfkkBaEaC3dOs8Q4kvxowoQWlAUmHv7fm0LYmmWqUejsXLPAyFoo5B

-- Dumped from database version 17.6 (Debian 17.6-1.pgdg12+1)
-- Dumped by pg_dump version 18.0

-- Started on 2025-10-23 11:06:28 WIB

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

--
-- TOC entry 3443 (class 1262 OID 25906)
-- Name: db_akademik_kampus; Type: DATABASE; Schema: -; Owner: postgres
--

CREATE DATABASE db_akademik_kampus WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'en_US.utf8';


ALTER DATABASE db_akademik_kampus OWNER TO postgres;

\unrestrict BKvNwkSg6QfkkBaEaC3dOs8Q4kvxowoQWlAUmHv7fm0LYmmWqUejsXLPAyFoo5B
\connect db_akademik_kampus
\restrict BKvNwkSg6QfkkBaEaC3dOs8Q4kvxowoQWlAUmHv7fm0LYmmWqUejsXLPAyFoo5B

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

--
-- TOC entry 217 (class 1259 OID 25907)
-- Name: seq_dosen; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.seq_dosen
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.seq_dosen OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 218 (class 1259 OID 25908)
-- Name: dosen; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.dosen (
    id_dosen integer DEFAULT nextval('public.seq_dosen'::regclass) NOT NULL,
    nip character varying(20) NOT NULL,
    nama_dosen character varying(100) NOT NULL,
    id_jurusan integer,
    email character varying(100),
    no_hp character varying(15) NOT NULL,
    status_aktif character(10) DEFAULT 'Aktif'::bpchar,
    CONSTRAINT dosen_status_aktif_check CHECK ((status_aktif = ANY (ARRAY['Aktif'::bpchar, 'Tidak Aktif'::bpchar])))
);


ALTER TABLE public.dosen OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 25914)
-- Name: seq_jadwal; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.seq_jadwal
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.seq_jadwal OWNER TO postgres;

--
-- TOC entry 220 (class 1259 OID 25915)
-- Name: jadwal_kuliah; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jadwal_kuliah (
    id_jadwal integer DEFAULT nextval('public.seq_jadwal'::regclass) NOT NULL,
    id_mk integer NOT NULL,
    id_dosen integer NOT NULL,
    id_kelas integer NOT NULL,
    tahun_akademik character varying(10) DEFAULT '2025/2026'::character varying,
    hari character varying(10),
    jam_mulai time without time zone DEFAULT '07:00:00'::time without time zone,
    jam_selesai time without time zone DEFAULT '18:00:00'::time without time zone,
    ruangan character varying(50)
);


ALTER TABLE public.jadwal_kuliah OWNER TO postgres;

--
-- TOC entry 221 (class 1259 OID 25922)
-- Name: seq_jurusan; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.seq_jurusan
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.seq_jurusan OWNER TO postgres;

--
-- TOC entry 222 (class 1259 OID 25923)
-- Name: jurusan; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jurusan (
    id_jurusan integer DEFAULT nextval('public.seq_jurusan'::regclass) NOT NULL,
    nama_jurusan character varying(100) NOT NULL,
    akreditasi character(1) DEFAULT 'A'::bpchar
);


ALTER TABLE public.jurusan OWNER TO postgres;

--
-- TOC entry 223 (class 1259 OID 25928)
-- Name: seq_kelas; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.seq_kelas
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.seq_kelas OWNER TO postgres;

--
-- TOC entry 224 (class 1259 OID 25929)
-- Name: kelas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.kelas (
    id_kelas integer DEFAULT nextval('public.seq_kelas'::regclass) NOT NULL,
    nama_kelas character varying(3) NOT NULL,
    id_jurusan integer
);


ALTER TABLE public.kelas OWNER TO postgres;

--
-- TOC entry 225 (class 1259 OID 25933)
-- Name: seq_mahasiswa; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.seq_mahasiswa
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.seq_mahasiswa OWNER TO postgres;

--
-- TOC entry 226 (class 1259 OID 25934)
-- Name: mahasiswa; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.mahasiswa (
    id_mahasiswa integer DEFAULT nextval('public.seq_mahasiswa'::regclass) NOT NULL,
    nim character varying(15) NOT NULL,
    nama_mahasiswa character varying(100) NOT NULL,
    id_jurusan integer,
    tahun_masuk integer DEFAULT EXTRACT(year FROM CURRENT_DATE),
    email character varying(100),
    jenis_kelamin character(1),
    no_hp character varying(15),
    semester integer DEFAULT 1,
    id_kelas integer,
    CONSTRAINT mahasiswa_jenis_kelamin_check CHECK ((jenis_kelamin = ANY (ARRAY['L'::bpchar, 'P'::bpchar])))
);


ALTER TABLE public.mahasiswa OWNER TO postgres;

--
-- TOC entry 227 (class 1259 OID 25941)
-- Name: seq_mk; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.seq_mk
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.seq_mk OWNER TO postgres;

--
-- TOC entry 228 (class 1259 OID 25942)
-- Name: matakuliah; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.matakuliah (
    id_mk integer DEFAULT nextval('public.seq_mk'::regclass) NOT NULL,
    kode_mk character varying(10) NOT NULL,
    nama_mk character varying(100) NOT NULL,
    sks integer NOT NULL,
    semester integer NOT NULL,
    id_jurusan integer
);


ALTER TABLE public.matakuliah OWNER TO postgres;

--
-- TOC entry 229 (class 1259 OID 25946)
-- Name: seq_nilai; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.seq_nilai
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.seq_nilai OWNER TO postgres;

--
-- TOC entry 230 (class 1259 OID 25947)
-- Name: nilai; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.nilai (
    id_nilai integer DEFAULT nextval('public.seq_nilai'::regclass) NOT NULL,
    id_mahasiswa integer NOT NULL,
    id_mk integer NOT NULL,
    nilai_angka integer DEFAULT 0,
    nilai_huruf character varying(2),
    tipe_nilai character varying(10),
    tanggal_input date DEFAULT CURRENT_DATE,
    CONSTRAINT nilai_tipe_nilai_check CHECK (((tipe_nilai)::text = ANY (ARRAY[('Tugas'::character varying)::text, ('UTS'::character varying)::text, ('UAS'::character varying)::text])))
);


ALTER TABLE public.nilai OWNER TO postgres;

--
-- TOC entry 3260 (class 2606 OID 25955)
-- Name: dosen dosen_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dosen
    ADD CONSTRAINT dosen_email_key UNIQUE (email);


--
-- TOC entry 3262 (class 2606 OID 25957)
-- Name: dosen dosen_nip_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dosen
    ADD CONSTRAINT dosen_nip_key UNIQUE (nip);


--
-- TOC entry 3264 (class 2606 OID 25959)
-- Name: dosen dosen_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dosen
    ADD CONSTRAINT dosen_pkey PRIMARY KEY (id_dosen);


--
-- TOC entry 3266 (class 2606 OID 25961)
-- Name: jadwal_kuliah jadwal_kuliah_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jadwal_kuliah
    ADD CONSTRAINT jadwal_kuliah_pkey PRIMARY KEY (id_jadwal);


--
-- TOC entry 3268 (class 2606 OID 25963)
-- Name: jurusan jurusan_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jurusan
    ADD CONSTRAINT jurusan_pkey PRIMARY KEY (id_jurusan);


--
-- TOC entry 3270 (class 2606 OID 25965)
-- Name: kelas kelas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.kelas
    ADD CONSTRAINT kelas_pkey PRIMARY KEY (id_kelas);


--
-- TOC entry 3272 (class 2606 OID 25967)
-- Name: mahasiswa mahasiswa_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mahasiswa
    ADD CONSTRAINT mahasiswa_email_key UNIQUE (email);


--
-- TOC entry 3274 (class 2606 OID 25969)
-- Name: mahasiswa mahasiswa_nim_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mahasiswa
    ADD CONSTRAINT mahasiswa_nim_key UNIQUE (nim);


--
-- TOC entry 3276 (class 2606 OID 25971)
-- Name: mahasiswa mahasiswa_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mahasiswa
    ADD CONSTRAINT mahasiswa_pkey PRIMARY KEY (id_mahasiswa);


--
-- TOC entry 3278 (class 2606 OID 25973)
-- Name: matakuliah matakuliah_kode_mk_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.matakuliah
    ADD CONSTRAINT matakuliah_kode_mk_key UNIQUE (kode_mk);


--
-- TOC entry 3280 (class 2606 OID 25975)
-- Name: matakuliah matakuliah_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.matakuliah
    ADD CONSTRAINT matakuliah_pkey PRIMARY KEY (id_mk);


--
-- TOC entry 3282 (class 2606 OID 25977)
-- Name: nilai nilai_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.nilai
    ADD CONSTRAINT nilai_pkey PRIMARY KEY (id_nilai);


--
-- TOC entry 3283 (class 2606 OID 25978)
-- Name: dosen fk_dosen_jurusan; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dosen
    ADD CONSTRAINT fk_dosen_jurusan FOREIGN KEY (id_jurusan) REFERENCES public.jurusan(id_jurusan);


--
-- TOC entry 3284 (class 2606 OID 25983)
-- Name: jadwal_kuliah fk_jdwl_dosen; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jadwal_kuliah
    ADD CONSTRAINT fk_jdwl_dosen FOREIGN KEY (id_dosen) REFERENCES public.dosen(id_dosen);


--
-- TOC entry 3285 (class 2606 OID 25988)
-- Name: jadwal_kuliah fk_jdwl_kelas; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jadwal_kuliah
    ADD CONSTRAINT fk_jdwl_kelas FOREIGN KEY (id_kelas) REFERENCES public.kelas(id_kelas);


--
-- TOC entry 3286 (class 2606 OID 25993)
-- Name: jadwal_kuliah fk_jdwl_mk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jadwal_kuliah
    ADD CONSTRAINT fk_jdwl_mk FOREIGN KEY (id_mk) REFERENCES public.matakuliah(id_mk);


--
-- TOC entry 3287 (class 2606 OID 25998)
-- Name: kelas fk_kelas_jurusan; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.kelas
    ADD CONSTRAINT fk_kelas_jurusan FOREIGN KEY (id_jurusan) REFERENCES public.jurusan(id_jurusan);


--
-- TOC entry 3288 (class 2606 OID 26003)
-- Name: mahasiswa fk_mahasiswa_jurusan; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mahasiswa
    ADD CONSTRAINT fk_mahasiswa_jurusan FOREIGN KEY (id_jurusan) REFERENCES public.jurusan(id_jurusan);


--
-- TOC entry 3289 (class 2606 OID 26008)
-- Name: mahasiswa fk_mhs_kls; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mahasiswa
    ADD CONSTRAINT fk_mhs_kls FOREIGN KEY (id_kelas) REFERENCES public.kelas(id_kelas);


--
-- TOC entry 3290 (class 2606 OID 26013)
-- Name: matakuliah fk_mk_jurusan; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.matakuliah
    ADD CONSTRAINT fk_mk_jurusan FOREIGN KEY (id_jurusan) REFERENCES public.jurusan(id_jurusan);


--
-- TOC entry 3291 (class 2606 OID 26018)
-- Name: nilai fk_nilai_mahasiswa; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.nilai
    ADD CONSTRAINT fk_nilai_mahasiswa FOREIGN KEY (id_mahasiswa) REFERENCES public.mahasiswa(id_mahasiswa);


--
-- TOC entry 3292 (class 2606 OID 26023)
-- Name: nilai fk_nilai_mk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.nilai
    ADD CONSTRAINT fk_nilai_mk FOREIGN KEY (id_mk) REFERENCES public.matakuliah(id_mk);


-- Completed on 2025-10-23 11:06:28 WIB

--
-- PostgreSQL database dump complete
--

\unrestrict BKvNwkSg6QfkkBaEaC3dOs8Q4kvxowoQWlAUmHv7fm0LYmmWqUejsXLPAyFoo5B

-- Completed on 2025-10-23 11:06:28 WIB

--
-- PostgreSQL database cluster dump complete
--

