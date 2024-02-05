--
-- PostgreSQL database dump
--

-- Dumped from database version 14.2
-- Dumped by pg_dump version 14.2

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Data for Name: currency; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.currency (id, code, name, description) VALUES (2, 643, 'RUB', 'Российский рубль');
INSERT INTO public.currency (id, code, name, description) VALUES (3, 840, 'USD', 'Доллар США');
INSERT INTO public.currency (id, code, name, description) VALUES (1, 978, 'EUR', 'Евро');


--
-- Data for Name: galleries; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.galleries (id, name, address) VALUES (1, 'Картинная галерея Лувра', 'Rue de Rivoli, 75001 Paris, Франция');
INSERT INTO public.galleries (id, name, address) VALUES (2, 'Музей Метрополитен', 'Нью-Йорк, Нью-Йорк, США');
INSERT INTO public.galleries (id, name, address) VALUES (3, 'Лондонская Национальная галерея', 'Trafalgar Square, London WC2N 5DN, Великобритания');


--
-- Data for Name: painters; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.painters (id, full_name, date_of_birth, telephone_number) VALUES (1, 'Ледянева Анастасия Дмитриевна', '2001-02-19', '7033244689');
INSERT INTO public.painters (id, full_name, date_of_birth, telephone_number) VALUES (2, 'Воболотный Никита Алексеевич', '2009-10-20', '7760526066');
INSERT INTO public.painters (id, full_name, date_of_birth, telephone_number) VALUES (3, 'Иголов Алексей Семёнович', '2000-05-11', '71052777970');
INSERT INTO public.painters (id, full_name, date_of_birth, telephone_number) VALUES (4, 'Катузова Ксения Олеговна', '2008-07-21', '79011034343');
INSERT INTO public.painters (id, full_name, date_of_birth, telephone_number) VALUES (5, 'Винсент Ван Гог', '1853-03-30', NULL);
INSERT INTO public.painters (id, full_name, date_of_birth, telephone_number) VALUES (6, 'Казимир Малевич', '1879-02-23', NULL);
INSERT INTO public.painters (id, full_name, date_of_birth, telephone_number) VALUES (7, 'Валентин Александрович Серов', '1865-01-19', NULL);
INSERT INTO public.painters (id, full_name, date_of_birth, telephone_number) VALUES (8, 'Ян Вермеер', '1632-10-01', NULL);
INSERT INTO public.painters (id, full_name, date_of_birth, telephone_number) VALUES (9, 'Альбрехт Дюрер', '1471-05-21', NULL);
INSERT INTO public.painters (id, full_name, date_of_birth, telephone_number) VALUES (10, 'Наталья Тюнева', NULL, '89853187535');
INSERT INTO public.painters (id, full_name, date_of_birth, telephone_number) VALUES (11, 'Лина Сокульская', NULL, NULL);
INSERT INTO public.painters (id, full_name, date_of_birth, telephone_number) VALUES (12, 'Сальвадор Дали', '1904-05-11', NULL);


--
-- Data for Name: pictures; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.pictures (id, name, painter_id, year) VALUES (72, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (73, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (74, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (75, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (76, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (77, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (78, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (83, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (84, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (85, 'Simple job', 1, '2000-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (86, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (1, 'Звездная ночь', 5, '1889-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (2, 'Черный квадрат', 6, '1915-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (3, 'Девочка с персиками', 7, '1887-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (4, 'Девушка с жемчужной сережкой', 8, '1665-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (5, 'Автопортрет с чертополохом', 9, '1493-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (6, 'Постоянство памяти', 12, '1934-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (7, 'Яхты', 10, '2019-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (8, 'Битва', 10, '1974-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (9, 'Шалуны', 11, '2015-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (10, 'Октябрь', 10, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (11, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (12, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (13, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (14, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (15, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (16, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (17, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (18, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (19, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (20, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (21, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (22, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (23, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (24, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (25, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (26, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (27, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (28, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (29, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (30, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (31, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (32, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (33, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (34, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (35, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (36, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (37, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (38, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (39, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (40, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (41, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (42, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (43, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (44, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (45, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (46, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (47, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (48, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (49, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (50, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (51, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (52, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (53, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (54, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (55, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (56, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (57, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (58, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (59, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (60, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (61, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (62, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (63, 'Simple job', 1, '2000-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (65, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (66, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (67, 'Picture', 2, '2021-01-01');
INSERT INTO public.pictures (id, name, painter_id, year) VALUES (68, 'Simple job', 1, '2000-01-01');


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.users (id, full_name, date_of_birth, telephone_number, login, password, role) VALUES (1, 'Покатышев Андрей Семёнович', '1999-11-01', '73430872941', 'admin', '$2y$10$4L2NZoBDzG0XLCO./F8hMu5XdYczyzCxYGPeUgRuE1wh5olGlpqwe', 'admin');
INSERT INTO public.users (id, full_name, date_of_birth, telephone_number, login, password, role) VALUES (2, 'Жаров Юрий Антонович', '1980-03-15', '7349792815', 'admin1', 'admin', 'admin');
INSERT INTO public.users (id, full_name, date_of_birth, telephone_number, login, password, role) VALUES (3, 'Левьентев Олег Игоревич', '1971-04-09', '73964951295', 'admin2', 'admin', 'admin');
INSERT INTO public.users (id, full_name, date_of_birth, telephone_number, login, password, role) VALUES (10, 'Иванов Иван Александрович', '2001-11-15', '79011047564', NULL, NULL, 'visitor');
INSERT INTO public.users (id, full_name, date_of_birth, telephone_number, login, password, role) VALUES (11, 'Курмызова Татьяна Игоревна', '2005-03-03', '710788325243', NULL, NULL, 'visitor');
INSERT INTO public.users (id, full_name, date_of_birth, telephone_number, login, password, role) VALUES (12, 'Сидорова Юлия Аркадиевна', '1984-07-12', '7122412429', NULL, NULL, 'visitor');
INSERT INTO public.users (id, full_name, date_of_birth, telephone_number, login, password, role) VALUES (4, 'Иванов Иван Александрович', '2001-11-15', '79011047564', NULL, NULL, 'visitor');
INSERT INTO public.users (id, full_name, date_of_birth, telephone_number, login, password, role) VALUES (5, 'Курмызова Татьяна Игоревна', '2005-03-03', '710788325243', NULL, NULL, 'visitor');
INSERT INTO public.users (id, full_name, date_of_birth, telephone_number, login, password, role) VALUES (6, 'Сидорова Юлия Аркадиевна', '1984-07-12', '7122412429', NULL, NULL, 'visitor');
INSERT INTO public.users (id, full_name, date_of_birth, telephone_number, login, password, role) VALUES (7, 'Иванов Иван Александрович', '2001-11-15', '79011047564', NULL, NULL, 'visitor');
INSERT INTO public.users (id, full_name, date_of_birth, telephone_number, login, password, role) VALUES (8, 'Курмызова Татьяна Игоревна', '2005-03-03', '710788325243', NULL, NULL, 'visitor');
INSERT INTO public.users (id, full_name, date_of_birth, telephone_number, login, password, role) VALUES (9, 'Сидорова Юлия Аркадиевна', '1984-07-12', '7122412429', NULL, NULL, 'visitor');


--
-- Data for Name: picture_purchase_reports; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (53, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (55, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (35, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (36, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (37, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (38, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (39, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (40, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (41, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (42, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (43, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (44, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (45, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (46, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (47, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (48, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (49, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (50, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (4, 12, 2, '2020-07-09 21:40:00', 12000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (6, 12, 5, '2020-07-09 21:40:00', 300000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (8, 12, 8, '2020-07-09 21:40:00', 25000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (54, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (56, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (58, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (60, 10, 72, '2348-09-27 13:29:00', 344, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (62, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (64, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (57, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (59, 10, 10, '2348-09-27 13:29:00', 344, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (61, 10, 72, '2020-08-01 11:05:00', 122, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (63, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (65, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (1, 10, 1, '2020-07-09 21:40:00', 15000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (2, 10, 7, '2020-07-09 21:40:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (3, 10, 3, '2020-07-09 21:40:00', 45000, 1);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (5, 10, 4, '2020-07-09 21:40:00', 100000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (7, 10, 4, '2020-07-09 21:40:00', 90000, 3);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (9, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (10, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (11, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (12, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (13, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (14, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (15, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (16, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (17, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (18, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (19, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (20, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (21, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (22, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (23, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (24, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (25, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (26, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (27, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (28, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (29, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (30, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (31, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (32, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (33, 10, 2, '2020-08-01 11:05:00', 30000, 2);
INSERT INTO public.picture_purchase_reports (id, visitor_id, picture_id, date_of_purchase, cost, currency_id) VALUES (34, 10, 2, '2020-08-01 11:05:00', 30000, 2);


--
-- Data for Name: tickets; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.tickets (id, gallery_id, date_of_visit, cost, currency_id) VALUES (5, 3, '2020-12-03', 2500, 2);
INSERT INTO public.tickets (id, gallery_id, date_of_visit, cost, currency_id) VALUES (4, 1, '2021-01-18', 2000, 2);
INSERT INTO public.tickets (id, gallery_id, date_of_visit, cost, currency_id) VALUES (2, 2, '2021-11-22', 3000, 2);
INSERT INTO public.tickets (id, gallery_id, date_of_visit, cost, currency_id) VALUES (3, 2, '2021-09-01', 1400, 3);
INSERT INTO public.tickets (id, gallery_id, date_of_visit, cost, currency_id) VALUES (1, 1, '2021-11-15', 500, 1);


--
-- Data for Name: ticket_purchase_reports; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (56, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (29, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (30, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (31, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (32, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (33, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (34, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (35, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (36, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (37, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (38, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (39, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (40, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (41, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (42, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (43, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (44, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (45, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (46, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (47, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (48, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (49, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (50, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (51, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (52, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (53, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (54, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (55, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (2, 11, 2, '2020-01-19 09:15:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (3, 12, 1, '2019-09-09 09:30:00', 500, 1);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (58, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (60, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (62, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (64, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (66, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (57, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (59, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (61, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (63, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (65, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (1, 10, 2, '2020-10-03 14:15:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (6, 10, 4, '2020-08-21 20:20:00', 2000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (9, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (10, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (11, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (12, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (13, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (14, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (15, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (16, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (17, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (18, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (19, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (20, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (21, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (4, 5, 3, '2018-11-15 14:20:00', 1400, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (5, 4, 5, '2019-11-13 05:01:00', 2500, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (7, 6, 1, '2020-07-09 21:40:00', 500, 3);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (8, 9, 1, '2019-03-01 13:06:00', 5000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (22, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (23, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (24, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (25, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (26, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (27, 10, 2, '2020-08-01 11:05:00', 3000, 2);
INSERT INTO public.ticket_purchase_reports (id, visitor_id, ticket_id, date_of_purchase, cost, currency_id) VALUES (28, 10, 2, '2020-08-01 11:05:00', 3000, 2);


--
-- Name: currency_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.currency_id_seq', 3, true);


--
-- Name: picture_purchase_reports_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.picture_purchase_reports_id_seq', 65, true);


--
-- Name: pictures_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.pictures_id_seq', 86, true);


--
-- Name: ticket_purchase_reports_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ticket_purchase_reports_id_seq', 66, true);


--
-- PostgreSQL database dump complete
--

