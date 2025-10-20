-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 30/09/2025 às 01:19
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `bookshelf`
--
CREATE DATABASE IF NOT EXISTS `bookshelf` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `bookshelf`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `books`
--

DROP TABLE IF EXISTS `books`;
CREATE TABLE IF NOT EXISTS `books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `author` varchar(100) DEFAULT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `avg_price` decimal(10,2) DEFAULT 0.00,
  `views` int(11) NOT NULL DEFAULT 0,
  `cover_image` varchar(255) DEFAULT 'default.jpg',
  `description` text DEFAULT NULL,
  `total_pages` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `fk_category` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELACIONAMENTOS PARA TABELAS `books`:
--   `category_id`
--       `categories` -> `id`
--   `category_id`
--       `categories` -> `id`
--

--
-- Despejando dados para a tabela `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `isbn`, `category_id`, `created_at`, `avg_price`, `views`, `cover_image`, `description`, `total_pages`) VALUES
(1, '1984', 'George Orwell', NULL, 1, '2025-09-20 00:55:20', 44.90, 4, '1984.jpg', 'Romance distópico sobre vigilância e controle totalitário.', 328),
(2, 'O Senhor dos Anéis', 'J.R.R. Tolkien', NULL, 3, '2025-09-20 00:55:20', 80.00, 7, 'senhor_dos_aneis.jpg', 'Uma épica jornada pela Terra-média para destruir o Um Anel.', 1178),
(3, 'Dom Casmurro', 'Machado de Assis', NULL, 2, '2025-09-20 00:55:20', 24.90, 3, 'dom_casmurro.jpg', 'Um clássico brasileiro sobre ciúme e memória.', 256),
(4, 'A Revolução dos Bichos', 'George Orwell', NULL, 1, '2025-09-20 00:55:20', 39.90, 1, 'revolucao_dos_bichos.jpg', 'Fábula satírica sobre política e poder.', 144),
(5, 'Harry Potter e a Pedra Filosofal', 'J.K. Rowling', NULL, 3, '2025-09-20 00:55:20', 49.90, 3, 'harry_potter_1.jpg', 'Início da saga do jovem bruxo Harry Potter.', 223),
(6, 'O Pequeno Príncipe', 'Antoine de Saint-Exupéry', NULL, 5, '2025-09-20 00:55:20', 21.90, 0, 'pequeno_principe.jpg', 'Uma história poética sobre amizade e essência da vida.', 96),
(7, 'Cem Anos de Solidão', 'Gabriel García Márquez', NULL, 2, '2025-09-20 00:55:20', 69.90, 2, 'cem_anos_solidão.jpg', 'Saga da família Buendía na mítica cidade de Macondo.', 417),
(8, 'O Código Da Vinci', 'Dan Brown', NULL, 4, '2025-09-20 00:55:20', 69.90, 0, 'codigo_da_vinci.jpg', 'Mistério envolvendo segredos religiosos e conspirações.', 489),
(10, 'O Hobbit', 'J.R.R. Tolkien', NULL, 3, '2025-09-20 00:55:20', 47.88, 1, 'hobbit.jpg', 'A jornada do hobbit Bilbo Bolseiro pela Terra-média.', 310),
(11, 'A Menina que Roubava Livros', 'Markus Zusak', NULL, 2, '2025-09-20 00:55:20', 69.90, 0, 'menina_que_roubava_livros.jpg', 'História de uma garota que encontra consolo nos livros durante a guerra.', 480),
(12, 'O Diário de Anne Frank', 'Anne Frank', NULL, 7, '2025-09-20 00:55:20', 49.90, 0, 'diario_anne_frank.jpg', 'Relato emocionante da vida de uma adolescente durante o Holocausto.', 283),
(13, 'O Alquimista', 'Paulo Coelho', NULL, 1, '2025-09-20 00:55:20', 69.90, 0, 'alquimista.jpg', 'Um jovem pastor segue seus sonhos em uma jornada espiritual.', 208),
(14, 'Memórias Póstumas de Brás Cubas', 'Machado de Assis', NULL, 2, '2025-09-20 00:55:20', 29.90, 0, 'bras_cubas.jpg', 'Romance satírico narrado pelo defunto-autor Brás Cubas.', 367),
(15, 'O Nome da Rosa', 'Umberto Eco', NULL, 4, '2025-09-20 00:55:20', 109.90, 0, 'nome_da_rosa.jpg', 'Mistério medieval envolvendo assassinatos e livros proibidos.', 512),
(16, 'A Guerra dos Tronos', 'George R.R. Martin', NULL, 3, '2025-09-20 00:55:20', 79.70, 0, 'guerra_dos_tronos.jpg', 'Conflitos pelo poder em Westeros.', 835),
(17, 'O Morro dos Ventos Uivantes', 'Emily Brontë', NULL, 2, '2025-09-20 00:55:20', 67.90, 0, 'morro_dos_ventos_uivantes.jpg', 'História de amor e vingança na Inglaterra do século XIX.', 360),
(18, 'O Grande Gatsby', 'F. Scott Fitzgerald', NULL, 1, '2025-09-20 00:55:20', 59.90, 0, 'grande_gatsby.jpg', 'Crítica à sociedade americana dos anos 20 através de Jay Gatsby.', 180),
(19, 'O Apanhador no Campo de Centeio', 'J.D. Salinger', NULL, 1, '2025-09-20 00:55:20', 74.90, 0, 'apanhador_campo_centeio.jpg', 'A história de Holden Caulfield e suas crises existenciais.', 277),
(20, 'A Cabana', 'William P. Young', NULL, 1, '2025-09-20 00:55:20', 59.90, 2, 'cabana.jpg', 'Reflexão sobre fé, dor e perdão.', 256),
(21, 'Como Fazer Amigos e Influenciar Pessoas', 'Dale Carnegie', NULL, NULL, '2025-09-20 00:55:20', 59.90, 0, 'cover_68d5484fae2f4.jpg', 'Guia clássico de desenvolvimento pessoal.', 288),
(22, 'O Senhor das Moscas', 'William Golding', NULL, 1, '2025-09-20 00:55:20', 79.90, 0, 'senhor_das_moscas.jpg', 'Jovens isolados em uma ilha enfrentam caos e violência.', 192),
(23, 'O Processo', 'Franz Kafka', NULL, 1, '2025-09-20 00:55:20', 29.90, 0, 'processo.jpg', 'A luta de um homem contra um sistema judicial absurdo.', 255),
(24, 'A Sombra do Vento', 'Carlos Ruiz Zafón', NULL, 2, '2025-09-20 00:55:20', 99.90, 1, 'sombra_do_vento.jpg', 'Mistério literário em Barcelona envolvendo livros esquecidos.', 532),
(25, 'O Segredo', 'Rhonda Byrne', NULL, NULL, '2025-09-20 00:55:20', 332.00, 0, 'segredo.jpg', 'Livro sobre a lei da atração e desenvolvimento pessoal.', 224),
(27, 'O Lobo da Estepe', 'Hermann Hesse', NULL, 1, '2025-09-20 00:55:20', 69.90, 0, 'lobo_da_estepe.jpg', 'Reflexão filosófica sobre dualidade e identidade.', 278),
(28, 'O Vendedor de Sonhos: O Chamado', 'Augusto Cury', NULL, 1, '2025-09-20 00:55:20', 29.90, 0, 'cover_68d547a20a014.jpg', 'Romance que mistura reflexão filosófica e drama humano.', 312),
(29, 'O Menino do Pijama Listrado', 'John Boyne', NULL, 5, '2025-09-20 00:55:20', 69.90, 0, 'menino_pijama.jpg', 'História de amizade durante o Holocausto.', 192),
(30, 'O Diário de um Mago', 'Paulo Coelho', NULL, 1, '2025-09-20 00:55:20', 74.90, 0, 'diario_mago.jpg', 'Narrativa de viagem espiritual e autodescoberta.', 256),
(31, 'Alice no País das Maravilhas', 'Lewis Carroll', NULL, 5, '2025-09-20 00:55:20', 69.90, 0, 'alice_pais_maravilhas.jpg', 'Clássico infantil sobre aventuras fantásticas.', 201),
(32, 'O Pequeno Nicolau', 'René Goscinny', NULL, 5, '2025-09-20 00:55:20', 76.50, 0, 'pequeno_nicolau.jpg', 'Histórias divertidas sobre a vida de um garoto francês.', 175),
(33, 'Jogos Vorazes', 'Suzanne Collins', NULL, 3, '2025-09-20 00:55:20', 50.84, 0, 'jogos_vorazes.jpg', 'Dystopia e luta pela sobrevivência em arena mortal.', 384),
(34, 'Maze Runner: Correr ou Morrer', 'James Dashner', NULL, 3, '2025-09-20 00:55:20', 77.00, 0, 'maze_runner.jpg', 'Jovens presos em labirinto precisam sobreviver e escapar.', 398),
(36, 'Percy Jackson e o Ladrão de Raios', 'Rick Riordan', NULL, 3, '2025-09-20 00:55:20', 59.90, 0, 'percy_jackson.jpg', 'Jovem descobre ser filho de um deus grego e inicia aventuras.', 369),
(37, 'O Chamado do Cuco', 'Robert Galbraith', NULL, 4, '2025-09-20 00:55:20', 49.90, 0, 'chamado_cuco.jpg', 'Mistério e investigação com detetive Cormoran Strike.', 412),
(38, 'O Silmarillion', 'J.R.R. Tolkien', NULL, 3, '2025-09-20 00:55:20', 69.90, 0, 'silmarillion.jpg', 'História da Terra-média antes de O Hobbit e O Senhor dos Anéis.', 487),
(39, 'Inferno', 'Dan Brown', NULL, 4, '2025-09-20 00:55:20', 69.90, 1, 'inferno.jpg', 'Mistério e conspirações em Florença envolvendo Dante Alighieri.', 460),
(40, 'Anjos e Demônios', 'Dan Brown', NULL, 4, '2025-09-20 00:55:20', 69.90, 0, 'anjos_demonios.jpg', 'Às vésperas do conclave que vai eleger o novo papa, Langdon é chamado às pressas para analisar um misterioso símbolo marcado a fogo no peito de um físico assassinado em um grande centro de pesquisas na Suíça.', 412),
(41, 'O Ponto da Virada', 'Malcolm Gladwell', NULL, NULL, '2025-09-20 00:55:20', 59.90, 1, 'ponto_virada.jpg', 'Como pequenas decisões causam grandes mudanças.', 320),
(42, 'O Homem Mais Rico da Babilônia', 'George S. Clason', NULL, NULL, '2025-09-20 00:55:20', 34.90, 0, 'homem_babilonia.jpg', 'Lições financeiras em forma de parábolas.', 202),
(43, 'A Menina do Vale', 'Bel Pesce', NULL, NULL, '2025-09-20 00:55:20', 19.01, 0, 'menina_vale.jpg', 'História de empreendedorismo e desafios profissionais.', 198),
(45, 'Os Homens que Não Amavam as Mulheres', 'Stieg Larsson', NULL, 4, '2025-09-20 00:55:20', 99.90, 11, 'homens_nao_amavam.jpg', 'Mistério e investigação na série Millennium.', 465),
(46, 'O Código da Vinci (Robert Langdon - Livro 2)', 'Dan Brown', NULL, 4, '2025-09-20 00:55:20', 69.90, 0, 'codigo_vinci_especial.jpg', 'Um assassinato no Museu do Louvre traz à tona uma sinistra conspiração para revelar um segredo que foi protegido por uma sociedade secreta desde os tempos de Jesus Cristo.', 502),
(47, 'O Médico e o Monstro', 'Robert Louis Stevenson', NULL, 1, '2025-09-20 00:55:20', 19.90, 0, 'medico_monstro.jpg', 'Dualidade do bem e do mal em narrativa clássica.', 224),
(51, 'Star Wars: Darth Vader', 'Greg Pak', NULL, 1, '2025-09-25 02:29:47', 58.00, 3, 'cover_68d54c71707d3.jpg', 'No clímax de The Empire Strikes Back, Darth Vader revelou seu verdadeiro relacionamento com Luke Skywalker e convidou seu filho para ficar ao seu lado. Mas uma escolha de Luke horrorizada de colocar no abismo sob a Cidade das Nuvens, em vez de se voltar para o Lado Negro.', 152);

-- --------------------------------------------------------

--
-- Estrutura para tabela `book_links`
--

DROP TABLE IF EXISTS `book_links`;
CREATE TABLE IF NOT EXISTS `book_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `link` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `book_id` (`book_id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELACIONAMENTOS PARA TABELAS `book_links`:
--   `book_id`
--       `books` -> `id`
--

--
-- Despejando dados para a tabela `book_links`
--

INSERT INTO `book_links` (`id`, `book_id`, `link`) VALUES
(1, 51, 'https://www.amazon.com.br/Star-Wars-Darth-Imperial-Machine/dp/1302907441/ref=sr_1_21?__mk_pt_BR=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=16RWWQFB42B66&dib=eyJ2IjoiMSJ9.xUtQwnQHw0K9qOCW5f1fwqsOS6N0sHmlUrvEcTAq4i5ohM2iYfUmG40MqXuQCedP-ZMv9O9InGDDd_oC0emL1gjqH9D'),
(6, 47, 'https://www.amazon.com.br/m%C3%A9dico-monstro-Robert-Louis-Stevenson/dp/859431812X/ref=sr_1_1?crid=3HA5IK1DPE099&dib=eyJ2IjoiMSJ9.2S-Dx0STZRbh5rGG5xTi5AD1I5Mjb7a3tuZllUARpBTzs8IfLkiEb-R84RM9IHAI8xHbAodTBbSB6K-yRslQJ8Wv4XVVRg2KoktpdLMKpRVaJtCfJqv9RL-wj-lbB'),
(7, 46, 'https://www.amazon.com.br/c%C3%B3digo-Vinci-Dan-Brown/dp/6555651040/ref=sr_1_1?crid=12AQI183931ZZ&dib=eyJ2IjoiMSJ9.wcnXUU2qgpm22-zNSNsoyt2OBXyu6EFVdgO9fS-MSXRoJQrGWnoF9_xhGv4Guv34SfqqAbPSg0buBkB2AiUE3o1clhrP7PrTlS3Yel4y-1pAXpLAggSWql_xGaIShVwspPKZ1Ctrq7Zl'),
(8, 45, 'https://www.amazon.com.br/homens-que-n%C3%A3o-amavam-mulheres/dp/853592616X/ref=sr_1_1?crid=1ITVHL9M1RMK7&dib=eyJ2IjoiMSJ9.H4M_QQAV-yWDZzUoanCpjM5waGyEEpDC86WGA2vwAwe6VsgZbKtFTQIha2SJCa7p.HcdjlwYEXRVIvQfpIsnjH6Y6zwiHNf2sC7LtoAnfELA&dib_tag=se&keywords=os+'),
(10, 43, 'https://www.amazon.com.br/Menina-do-Vale-Bel-Pesce/dp/8577342808/ref=sr_1_1?__mk_pt_BR=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=1M8MWLLB05N9R&dib=eyJ2IjoiMSJ9.T6y-4LKYt_X8ZnADEdce1hJumOGMgU9Y-H31FEGTKqRSmNwaPEtzvXxW-q4kJdK3mJvBdRwgNnODJCJAKMcpzMmwN39qUhhjYr8C'),
(11, 42, 'https://www.amazon.com.br/Homem-Mais-Rico-Babil%C3%B4nia/dp/8595081530/ref=sr_1_1?crid=133E2JLPU9OXC&dib=eyJ2IjoiMSJ9.vr3cqICSg2vvyClkSpjR6WlmDUo-vmNXF4BS5EVXeN1xXiimZTBPRX-WQQciTAkgmT6iyJ6WjOg_ObSp7LNelNV2Io43QT6nVaD_wnYNbb1BvGmViV3ZYukJX202K1rwDLUHj0VT7'),
(12, 41, 'https://www.amazon.com.br/ponto-virada-Tipping-pequenas-diferen%C3%A7a-ebook/dp/B00B1B0VOC/ref=sr_1_1?__mk_pt_BR=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=2PAWZTHI8I0AF&dib=eyJ2IjoiMSJ9.xON1qcDdh7dqDogNyBKMr9B-eIQh5TRWAn1QmRHyeZqIw9SFT61LDIqah_oYtDCFHSfi9kTpe1'),
(13, 40, 'https://www.amazon.com.br/sspa/click?ie=UTF8&spc=MTo3OTQyNTQzNDAyMjk3OTYzOjE3NTg4MDY4OTQ6c3BfYXRmOjMwMDc5NTI1MDMxNTUwMjo6MDo6&url=%2FAnjos-dem%25C3%25B4nios-Robert-Langdon-Livro%2Fdp%2F6555658150%2Fref%3Dsr_1_1_sspa%3Fcrid%3D3AJKFOXQVYMM7%26dib%3DeyJ2Ijoi'),
(14, 39, 'https://www.amazon.com.br/Inferno-Dan-Brown/dp/8580411521/ref=sr_1_2?crid=WO6JLHSG3UTT&dib=eyJ2IjoiMSJ9.P1lmUN8u6Gan_qXdPUcaT1lJUqNEFxbtIJpN9o7o1UWMUY0AZUH41xZ7LQgbaQuyjqGcaHtnRhccCG_Fb9i8p9UpPvLfhFhw3hope4VwgNy-JrjOVcPzEfTrVQKLsnU5FRnPphbZBEJ6UvDXpwIPW_C'),
(15, 38, 'https://www.amazon.com.br/Silmarillion-J-R-Tolkien/dp/8595084378/ref=sr_1_1?__mk_pt_BR=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=FZSKE6B494PS&dib=eyJ2IjoiMSJ9.v8hiChBsG7sDlvgoAJthU8JKnLXGx5XszHvLJxMNRnaaZfwZ1FZ9cZsBflQCMmRxi9EG6yToxeA_fKU0X1Eh1-25K6pMIRGeoMmL1'),
(16, 37, 'https://www.amazon.com.br/Chamado-do-Cuco-Robert-Galbraith/dp/8532528740/ref=sr_1_1?__mk_pt_BR=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=3G5Q165YFCPTY&dib=eyJ2IjoiMSJ9.ojBTu9WMoNDcnQeFShG5A8XScl3XzUCyGuft3znWGmuN3ShNVTDLGYTMqRAzqw-aTeDeb1COy2n9cNb1oREb3H83MhVh'),
(17, 36, 'https://www.amazon.com.br/ladr%C3%A3o-raios-S%C3%A9rie-Jackson-olimpianos/dp/6555606533/ref=sr_1_1?__mk_pt_BR=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=1LBQZAI2BVJGJ&dib=eyJ2IjoiMSJ9.57YrOZbu9EdDXDewhpFYRqjGIAyo-zBY1lwEpMiZwae27ICOhY8ZvI3J4Llv-AdWijANevhe5IC4b'),
(18, 10, 'https://www.amazon.com.br/Hobbit-p%C3%B4ster-J-R-R-Tolkien/dp/8595084742/ref=sr_1_4?__mk_pt_BR=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=2VYDJ3GQKQ0YM&dib=eyJ2IjoiMSJ9._3iRKavAJLPhkelzbRAslcqaRSURzOKIyNVftQYkvl4JbD_NGg2WIEKoZX47_xtRH0d-WI3c6BlAXrs5ITiPMg-QmXl3'),
(19, 34, 'https://www.amazon.com.br/Runner-Correr-Morrer-James-Dashner/dp/857683247X/ref=sr_1_1?__mk_pt_BR=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=23XFG5E82LSBR&dib=eyJ2IjoiMSJ9.Xm-Vn_eoiAXNyL2Mno9p9a5iOSiMaP11aHvvkMYQuI_w4Jkveq42MRyekRR3enfM9EphiRIFB0nNv74zAmZKqHqkPB'),
(20, 33, 'https://www.amazon.com.br/Jogos-vorazes-Trilogia-Vorazes-Livro-ebook/dp/B00A3D1OYU/ref=sr_1_2?crid=O1Q244QK8LR0&dib=eyJ2IjoiMSJ9.PiCjyFFeRjIKGXfepC3rbnL9OXFBpRjXzqk8v8U7O0_rgH3rIDyDk9n07Snm29IiE8fzKK8VYbDFf1nmb-tnGoHC-2PHsG1akUwF-6M3mbKwdRRJWbiGUZwnHL83Y2'),
(21, 32, 'https://www.amazon.com.br/Pequeno-Nicolau-Jean-Semp%C3%A9/dp/8533608225/ref=sr_1_1?__mk_pt_BR=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=1I3E71IB5695W&dib=eyJ2IjoiMSJ9.wPxhQPqrdaNTpE4qZKYY3o67pV8bKxQ0GeKBMwq7QG7oEHToGS3hI03m4oZk3vPAD0XtVmx2qMPhkXWsD_KOIP1DOmpsN'),
(22, 31, 'https://www.amazon.com.br/Alice-Pa%C3%ADs-das-Maravilhas-Classic/dp/8594541759/ref=sr_1_1?crid=2HAZLB9J635ZC&dib=eyJ2IjoiMSJ9.SFA0DQc1qKcQ8VQXKf2Mrw8U2F-9M6rAXBfpuWCP58URASs2SiR7bSJ_VNVyKHznbfKGTeO-73sTmNQHKN3NzPna54LejDQarmED-TBkfBzNIIEfN8MdrZ_RKrCFICLDE'),
(23, 30, 'https://www.amazon.com.br/di%C3%A1rio-um-mago-Paulo-Coelho/dp/8584390707/ref=sr_1_1?__mk_pt_BR=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=3LN57CTBSHLBS&dib=eyJ2IjoiMSJ9.d8UoNfVDmZjuG_yGPnfUiHmX7Gw3q-H99l-TZmhELCj3RUJ1_zkzZX7jXJbYRnFWDcP8hGxdJfe_lTcvMTSGWyYn-Nze'),
(24, 29, 'https://www.amazon.com.br/menino-do-pijama-listrado/dp/853591112X/ref=sr_1_1?__mk_pt_BR=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=28V4OJAP2JSP3&dib=eyJ2IjoiMSJ9.HgndO6vDB1PRwjtnSJFSLxqyOLMbLMFRvKyIroBi00hExl5E-L9HJCfuypmfM7gqq6cZZR_Os_5Bg0xnNiTsOgQipvVN69ycdgu'),
(25, 28, 'https://www.amazon.com.br/VENDEDOR-SONHOS-1-CHAMADO/dp/6555246138/ref=sr_1_1?crid=WBW1M38I4J50&dib=eyJ2IjoiMSJ9.Bd0WbMaaOnuf20kv7bPHl8KWGrAs2W5HHwAyO1k27VPjnTrSuMuHcDOF8gJL-QgpoTYgeQ49P9UXVI1SAmLPhkSNqSAl1YIa5Vr_ZyrtkfCQz7APY6hJ5lZzB4NAnLYXBCnL4fdIfwqBjyk'),
(26, 27, 'https://www.amazon.com.br/lobo-estepe-Hermann-Hesse/dp/8501113883/ref=sr_1_1?crid=3483HINWF881H&dib=eyJ2IjoiMSJ9.aV-h2LQIXgeWThGYsow9zX26or8mnlfUxZCtuVYpU_CynL2TaxC6ahIpku6Eem4wSAW-G9SOuP9m9PC1R64OcPTaaiOWnjQxslnKRm8ucLZFL2zyDO_pplel-oXjR-WoGCsbjbvxnd70ru'),
(27, 25, 'https://www.amazon.com.br/Segredo-Rhonda-Byrne/dp/8543101964/ref=sr_1_2?__mk_pt_BR=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=179O7TUXKSSMO&dib=eyJ2IjoiMSJ9.vgM1zNufxJl13gD6HkXWzaEK5W3oTvqro-4Q7VmhQOc1Xf9R6vt1rXgcHUM6B5lGhROkFv0DUci85vsdtQagIT2A5GDBCyHZcifJUook'),
(28, 24, 'https://www.amazon.com.br/sombra-vento-Carlos-Ruiz-Zaf%C3%B3n/dp/8556510345/ref=sr_1_1?crid=3A7AOVD8LIZFH&dib=eyJ2IjoiMSJ9.D5FlnqG3GQT42eQMSv1zd2JSx6XJGfc-52pP1iZzWLe-d8Njf-H5alvv10yEGhbW9YcHwZXpMS8O66B2a6B49tEfDUwRxdlAPTjPbtGh7bKg3izC18lhX2fyt4kibXyDlDvJ'),
(29, 23, 'https://www.amazon.com.br/processo-Franz-Kafka/dp/6555520051/ref=sr_1_2?crid=3ID11AT9RYYBW&dib=eyJ2IjoiMSJ9.nXZVNdnO0RC_qSRjKfAsxykolqPfv8bje2M-1FNaFLonUSqffsSXpGUDu3DGCGK8SKVUToiXn3AEtePg4uKql-ew29EQaWwd2H2Y0O61pK-Blmwy9q3y1EVp1Et4IuYPXvrqCMp-uKI4Jjd0n1O'),
(30, 22, 'https://www.amazon.com.br/Senhor-das-Moscas-Nova-edi%C3%A7%C3%A3o/dp/8556521223/ref=sr_1_1?crid=2XZY0GHM8QF22&dib=eyJ2IjoiMSJ9.8EwPfGrY5XSJKpQmx1xseck2Qj4wZkvw-TOjIQxs283LXf9RCavh19mYaQBRS4XDPcB-XKnvgvJcVQPPwCV_3sRvRg6Wn4-ch--Op4U0QxIFTLankoxQ2gvWIsvmtqVd'),
(31, 21, 'https://www.amazon.com.br/Como-fazer-amigos-influenciar-pessoas/dp/8543108683/ref=sr_1_1?crid=2XWCFWU8CRGPH&dib=eyJ2IjoiMSJ9.bSFCvfCcd7GAvO2EffXn6g85UzsTmgB_gkAkdAEm8e6X7ru7oUc_pO9KA24DzmiPXhxowxwH9QfPifhENRV3XLh_Cl3WoIw42sDkw4rqAvTXuwAkxdI-l2mN0fZMsMCRqD'),
(32, 20, 'https://www.amazon.com.br/Cabana-William-P-Young/dp/8599296361/ref=sr_1_1?__mk_pt_BR=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=1TQ3Z70ADGWWT&dib=eyJ2IjoiMSJ9.gDiOhZqF97jaCDb0IJ3XFUmVr3KkZnrEV8MNqeyK4WNAOXTakXTUsfG7y_B_QdSDaYxt6RBq3MqkkQzolYYMdXvNyhpFyNAfbYyuyh'),
(33, 19, 'https://www.amazon.com.br/apanhador-no-campo-centeio/dp/6580309032/ref=sr_1_1?crid=1B8VPVNBHBHUJ&dib=eyJ2IjoiMSJ9.cF9LaTrVml56ZOtCJg2HeiC_kL-MW6IrwZN7hSz7Y3MqgFDhLqwhlZVbnbnrG0cRImoBIoJaBeV2yAn5qOvKrU6Cwv0pP7s0QKIdnMaIYzoX-W-J2F7j2KK6FSVefFuDzTiWCC9qaJjQ_'),
(34, 18, 'https://www.amazon.com.br/grande-Gatsby-F-Scott-Fitzgerald/dp/8563560298/ref=sr_1_1?__mk_pt_BR=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=35QLLMOM8SSCA&dib=eyJ2IjoiMSJ9.hhL1Pz_kCxfa62YFUWfv23NnWKalUz-nD2ZYB3aV8J2Po3EBzi-48YdNgGQ876sSlCZAHzdGN0rlsBt8ZKXjqeVfpem2'),
(35, 17, 'https://www.amazon.com.br/sspa/click?ie=UTF8&spc=MToyMDE5NTY3MzExMTM0NjU3OjE3NTg4MDg2NTg6c3BfYXRmOjMwMDg4OTAxNjkyNDIwMjo6MDo6&url=%2FMorro-dos-Ventos-Uivantes-Especial%2Fdp%2F6587034675%2Fref%3Dsr_1_1_sspa%3F__mk_pt_BR%3D%25C3%2585M%25C3%2585%25C5%25BD%25'),
(36, 16, 'https://www.amazon.com.br/Guerra-dos-Tronos-Cr%C3%B4nicas-Gelo/dp/8556510787/ref=sr_1_1?__mk_pt_BR=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=U1ZSBKD64BT0&dib=eyJ2IjoiMSJ9.QM2WzfZl_d8FRjfV5wpRXWEpfhhqEzAT1UTcoyphTPcvWffHy6vhWIpWaxh1ozJfUS-M6dVQhYZH0NU24Ep4GG8e_'),
(37, 15, 'https://www.amazon.com.br/nome-rosa-Graphic-Novel-Vol/dp/6555877987/ref=sr_1_2?crid=RBTWH7AVM5ZN&dib=eyJ2IjoiMSJ9.uKzRbjP9DykHBP6CeL1aMEeit-AakXuo2D73GjUOF5ByJQBQd74dd2-lvtH1qS1w7kIUZweRI8vpcRIXM-ZvRtt7g88gNG-lEnVSFs85CZP7z0Fssi3kPmi77F_INKYnlmlZh-kfaNivg'),
(38, 14, 'https://www.amazon.com.br/Mem%C3%B3rias-p%C3%B3stumas-Cubas-Machado-Assis/dp/6555246936/ref=sr_1_2?__mk_pt_BR=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=2FADS8YM99KMO&dib=eyJ2IjoiMSJ9.dGuW1od2A0ywew4G-hLoPNoC8HraFkyXdLKFqYVoAQt-t3_QhPPsOyVVcsCAsOCHKpMC4gHhpJwn0'),
(39, 8, 'https://www.amazon.com.br/c%C3%B3digo-Vinci-Dan-Brown/dp/6555651040/ref=sr_1_1?crid=1C6L5Y8QRM0WQ&dib=eyJ2IjoiMSJ9.wcnXUU2qgpm22-zNSNsoyt2OBXyu6EFVdgO9fS-MSXRoJQrGWnoF9_xhGv4Guv34SfqqAbPSg0buBkB2AiUE3o1clhrP7PrTlS3Yel4y-1pAXpLAggSWql_xGaIShVwspPKZ1Ctrq7Zl'),
(40, 13, 'https://www.amazon.com.br/alquimista-Paulo-Coelho/dp/8584390677/ref=sr_1_1?__mk_pt_BR=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=31ZQS5MAW0YT0&dib=eyJ2IjoiMSJ9.fQPB1hSexZEAlzIz9iqkX_MHYEghr3yJ-lT6LLXhKqdHUagWsqxYo6W7S5MZeZ0-wIESRDPjIA0g_HcBeOS7oLKPRlJk7G2WDx137'),
(41, 12, 'https://www.amazon.com.br/di%C3%A1rio-Anne-Frank/dp/8501044458/ref=sr_1_2?__mk_pt_BR=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=PE0FKHHPVN5D&dib=eyJ2IjoiMSJ9.Dk52d4M-kIVccKwIpyuui7I4pssck1Gwo9ijPHymwa1GhoPdAYxXVvuMJfGMzEcSLe8QuCFd0OKInMMrs5qpaLyeEif3eHnep_SqZS3'),
(42, 11, 'https://www.amazon.com.br/Menina-que-Roubava-Livros/dp/8598078174/ref=sr_1_1?__mk_pt_BR=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=2UB29564JVK9F&dib=eyJ2IjoiMSJ9.uJL_OQURdT_wThYdRyowXbztNfzyuf8LSyDws6imw527ZnOzuyhuTsa7eKF9I8qL6xLyUdml1rjMA8LmO-4eYYa1NXLz0-d79Ly'),
(43, 7, 'https://www.amazon.com.br/anos-solid%C3%A3o-Gabriel-Garcia-M%C3%A1rquez/dp/8501012076/ref=sr_1_1?__mk_pt_BR=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=2JVHL5KMQQI4R&dib=eyJ2IjoiMSJ9.HVlY1q_-fD6Q5B1xy8ILUUV14H4Cy8-WY2e87Xxa5Y2VVsySoNcC9KOnhXOMAQhjrR-qZieZ9Hd75Ys'),
(44, 6, 'https://www.amazon.com.br/Pequeno-Pr%C3%ADncipe-Original-Tradu%C3%A7%C3%A3o-aquarelas/dp/8595081514/ref=sr_1_3?crid=2EKXDA1IOHN2J&dib=eyJ2IjoiMSJ9.11xzBQ_2_v2fkFa3a14_3vRWGHpepkcfcSrScKGoxjKVwBSgG-ziQNoCZhORpOTY4JphfcH5sH6LspxNmTb_MWrZlw1aI0fIfczx1S6upCeJ'),
(45, 5, 'https://www.amazon.com.br/Harry-Potter-Pedra-Filosofal-Rowling/dp/8532511015/ref=sr_1_3?__mk_pt_BR=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=1O9RCWJHVI4R3&dib=eyJ2IjoiMSJ9.JBKP3hH9J2Fd5zikFzctnMN_sWrQx6VXZ1ubvnUkvvkCs3r0UYUukaMQ6r_GuziMi693-VKzLXIcinm5HujrW1d9'),
(46, 4, 'https://www.amazon.com.br/revolu%C3%A7%C3%A3o-dos-bichos-conto-fadas/dp/8535909559/ref=sr_1_1?__mk_pt_BR=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=1JTIEYANXU0EU&dib=eyJ2IjoiMSJ9.RvB4L0x02qK0x8gy_ct5tW7_XW0JUhdWyrf_-sYYF5NTcK0QUiaxBUlECKLlfa6FWy1ycJoJ9qiyqgMLJR'),
(47, 3, 'https://www.amazon.com.br/Dom-Casmurro-Machado-Assis/dp/859431860X/ref=sr_1_2?crid=MRANRECU9ZGF&dib=eyJ2IjoiMSJ9.KO_3Y6WY8BRirA61lvCqBD2a2d6TJy_xmwnzEe6UvaRy0GJvpD2fgXVotzpYf2Y4zRWee5o8IQGsJLu_8Zhh-IZX1gzoPkUsFjyUZU_qypHKJN6p3LSWxSnfCKFhAk2rXP9NSSfLPYPpaH'),
(48, 2, 'https://www.amazon.com.br/Senhor-dos-An%C3%A9is-%C3%9Anico/dp/8533613407/ref=sr_1_9?crid=8MRYYWQ1TCEU&dib=eyJ2IjoiMSJ9.dkIm0tv7Ww2JHze9ADKXyRb5kHj0nv9DahFquSd1LxOH6UgUayNNh7IIwWQsCLAZLcmwlrA5JvTleZIhoRNdkyXEV6C-nsOwvNUteR2JKgDbOkrZqdkY5DJtkfb5jtE0e-z9vnMx'),
(49, 1, 'https://www.amazon.com.br/1984-George-Orwell/dp/8535914846/ref=sr_1_1?__mk_pt_BR=%C3%85M%C3%85%C5%BD%C3%95%C3%91&crid=3SVC611Z3ZVSI&dib=eyJ2IjoiMSJ9.YmAQI_v2aNNn_GqixoPw_cZ9SrolFgyWF7QQs_TvpLAlPboWLdWj5L7RrljgTwQZ4T8d3eqSwnVXR-j7Jyt2TVkDJppz1sOv4fQzKoVmvP');

-- --------------------------------------------------------

--
-- Estrutura para tabela `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELACIONAMENTOS PARA TABELAS `categories`:
--

--
-- Despejando dados para a tabela `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(8, 'Ciência'),
(3, 'Fantasia'),
(1, 'Ficção'),
(7, 'História'),
(5, 'Infantil'),
(6, 'Não-ficção'),
(2, 'Romance'),
(4, 'Suspense');

-- --------------------------------------------------------

--
-- Estrutura para tabela `favorites`
--

DROP TABLE IF EXISTS `favorites`;
CREATE TABLE IF NOT EXISTS `favorites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `book_id` (`book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELACIONAMENTOS PARA TABELAS `favorites`:
--   `user_id`
--       `users` -> `id`
--   `book_id`
--       `books` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `pages`
--

DROP TABLE IF EXISTS `pages`;
CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `page_number` int(11) NOT NULL,
  `content` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `book_id` (`book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELACIONAMENTOS PARA TABELAS `pages`:
--   `book_id`
--       `books` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `status` tinyint(1) DEFAULT 1,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token_hash` varchar(255) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELACIONAMENTOS PARA TABELAS `users`:
--

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `name`, `password`, `role`, `status`, `email`, `created_at`, `reset_token_hash`, `reset_expires`) VALUES
(1, 'João', '$2y$10$oEElNmLXGs7u5HX.Yx9bXOoKHsY2/4CfPMk6qXsuHg0JtfYTkmzuS', 'user', 1, 'jp.kronhardt0506@gmail.com', '2025-09-20 00:26:41', '$2y$10$q08rEIuc3oYYnBInOWYES.Jxiq2FA4tqm2LrgzBEiLbpiz059srdy', '2025-09-30 01:44:26'),
(3, 'Kronhardt', '$2a$12$/fvXaIs8J4/flSMPnNTMj.a416Z7vYjHw/tVrGTW93jEjO.v4u7BC', 'admin', 1, 'jp.kronhardt05@gmail.com', '2025-09-20 00:38:32', '$2y$10$nLCN3nnMO7zu8qM/Ly3lTeLzvPvsllwmzKhnNdTI8rl5WPHDt8Ma.', '2025-09-26 21:48:21');

-- --------------------------------------------------------

--
-- Estrutura para tabela `user_activity`
--

DROP TABLE IF EXISTS `user_activity`;
CREATE TABLE IF NOT EXISTS `user_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `book_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `book_id` (`book_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELACIONAMENTOS PARA TABELAS `user_activity`:
--   `user_id`
--       `users` -> `id`
--   `book_id`
--       `books` -> `id`
--

--
-- Despejando dados para a tabela `user_activity`
--

INSERT INTO `user_activity` (`id`, `user_id`, `action`, `book_id`, `created_at`) VALUES
(5, 1, 'added', 47, '2025-09-21 20:36:54'),
(6, 1, 'finished', 47, '2025-09-21 20:38:18'),
(8, 1, 'added', 46, '2025-09-22 02:30:38'),
(9, 1, 'added', 45, '2025-09-22 02:30:42'),
(11, 1, 'added', 43, '2025-09-22 02:30:50'),
(12, 1, 'added', 42, '2025-09-22 02:30:55'),
(13, 1, 'added', 41, '2025-09-22 02:30:58'),
(14, 1, 'added', 40, '2025-09-22 02:31:02'),
(15, 1, 'removed', 47, '2025-09-22 02:53:18'),
(24, 1, 'added', 51, '2025-09-27 02:10:20'),
(25, 1, 'finished', 51, '2025-09-27 19:26:16'),
(27, 1, 'finished', 43, '2025-09-29 00:25:49'),
(31, 1, 'finished', 42, '2025-09-29 22:56:39');

-- --------------------------------------------------------

--
-- Estrutura para tabela `user_comments`
--

DROP TABLE IF EXISTS `user_comments`;
CREATE TABLE IF NOT EXISTS `user_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `book_id` (`book_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELACIONAMENTOS PARA TABELAS `user_comments`:
--   `user_id`
--       `users` -> `id`
--   `book_id`
--       `books` -> `id`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `user_library`
--

DROP TABLE IF EXISTS `user_library`;
CREATE TABLE IF NOT EXISTS `user_library` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `current_page` int(11) DEFAULT 0,
  `total_pages` int(11) DEFAULT NULL,
  `status` enum('added','reading','finished') DEFAULT 'added',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_book` (`user_id`,`book_id`),
  KEY `book_id` (`book_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELACIONAMENTOS PARA TABELAS `user_library`:
--   `user_id`
--       `users` -> `id`
--   `book_id`
--       `books` -> `id`
--

--
-- Despejando dados para a tabela `user_library`
--

INSERT INTO `user_library` (`id`, `user_id`, `book_id`, `added_at`, `current_page`, `total_pages`, `status`) VALUES
(8, 1, 46, '2025-09-22 02:30:38', 0, NULL, 'added'),
(9, 1, 45, '2025-09-22 02:30:42', 0, NULL, 'added'),
(11, 1, 43, '2025-09-22 02:30:50', NULL, NULL, 'finished'),
(12, 1, 42, '2025-09-22 02:30:55', NULL, NULL, 'finished'),
(13, 1, 41, '2025-09-22 02:30:58', 0, NULL, 'added'),
(14, 1, 40, '2025-09-22 02:31:02', 0, NULL, 'added'),
(19, 1, 51, '2025-09-27 02:10:20', NULL, NULL, 'finished');

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Restrições para tabelas `book_links`
--
ALTER TABLE `book_links`
  ADD CONSTRAINT `book_links_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `pages`
--
ALTER TABLE `pages`
  ADD CONSTRAINT `pages_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `user_activity`
--
ALTER TABLE `user_activity`
  ADD CONSTRAINT `user_activity_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_activity_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);

--
-- Restrições para tabelas `user_comments`
--
ALTER TABLE `user_comments`
  ADD CONSTRAINT `user_comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_comments_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `user_library`
--
ALTER TABLE `user_library`
  ADD CONSTRAINT `user_library_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_library_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
