-- phpMyAdmin SQL Dump
-- version 4.0.4.2
-- http://www.phpmyadmin.net
--
-- Máquina: localhost
-- Data de Criação: 26-Out-2026 às 23:46
-- Versão do servidor: 5.6.13
-- versão do PHP: 5.4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de Dados: `cadastro_produto`
--
CREATE DATABASE IF NOT EXISTS `cadastro_produto` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `cadastro_produto`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `cadastro_prod`
--

CREATE TABLE IF NOT EXISTS `cadastro_prod` (
  `descricao` varchar(70) NOT NULL,
  `categoria` varchar(70) NOT NULL,
  `valorcompra` decimal(10,2) NOT NULL,
  `valorvenda` decimal(10,2) NOT NULL,
  `qtdestoque` int(11) NOT NULL,
  `lucrounit` decimal(10,2) NOT NULL,
  `idproduto` int(11) NOT NULL AUTO_INCREMENT,
  `imagem` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idproduto`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Extraindo dados da tabela `cadastro_prod`
--

INSERT INTO `cadastro_prod` (`descricao`, `categoria`, `valorcompra`, `valorvenda`, `qtdestoque`, `lucrounit`, `idproduto`, `imagem`) VALUES
('carrinho maneiro', 'brinquedo', '70.99', '100.99', 1321, '30.00', 6, 'produto_6adfd810ad1339.22560571.jpg'),
('carrinho rapido', 'brinquedo', '10.99', '30.99', 10, '20.00', 7, 'produto_6adfe1ba046a13.19709341.jpg'),
('carrinho hotwhells generico1', 'brinquedo', '546.87', '986.99', 7, '440.12', 8, 'produto_6adfe5ce828239.05261100.jpg'),
('carrinho hotwhells generico1', 'brinquedo', '546.87', '986.99', 7, '440.12', 9, 'produto_6adfe5f58848f5.92802063.jpg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_login`
--

CREATE TABLE IF NOT EXISTS `tb_login` (
  `idconta` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` varchar(20) NOT NULL DEFAULT 'cliente',
  PRIMARY KEY (`idconta`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `tb_login`
--

INSERT INTO `tb_login` (`idconta`, `usuario`, `email`, `senha`, `tipo`) VALUES
(1, 'robson', 'robson@gmail.com', '1234', 'admin'),
(2, 'loquinho', 'loquinho@gmail.com', '1234', 'cliente');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
