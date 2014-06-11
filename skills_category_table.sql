CREATE TABLE IF NOT EXISTS `kba07_pf_skill_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `kba07_pf_skill_category`
--

INSERT INTO `kba07_pf_skill_category` (`id`, `category`) VALUES
(1, 'IT & Programming'),
(2, 'Writing & Translation'),
(3, 'Design & Multimedia'),
(4, 'Sales & Marketing'),
(5, 'Admin Support'),
(6, 'Engineering & Manufacturing'),
(7, 'Finance & Management'),
(8, 'Legal');