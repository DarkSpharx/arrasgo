-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: arrasgo
-- ------------------------------------------------------
-- Server version	11.8.2-MariaDB-ubu2404

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `chapitres`
--

DROP TABLE IF EXISTS `chapitres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chapitres` (
  `id_chapitre` int(11) NOT NULL AUTO_INCREMENT,
  `id_etape` int(11) NOT NULL,
  `titre_chapitre` varchar(255) DEFAULT NULL,
  `texte_chapitre` text DEFAULT NULL,
  `ordre_chapitre` int(11) DEFAULT NULL,
  `image_chapitre` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_chapitre`),
  KEY `id_etape` (`id_etape`),
  CONSTRAINT `chapitres_ibfk_1` FOREIGN KEY (`id_etape`) REFERENCES `etapes` (`id_etape`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chapitres`
--

LOCK TABLES `chapitres` WRITE;
/*!40000 ALTER TABLE `chapitres` DISABLE KEYS */;
INSERT INTO `chapitres` VALUES (1,1,'','Madame, Monsieur, bienvenue dans l\'histoire du théâtre arrageois !\r\nSelon l\'historien Adolphe de Cardevacque, \"l\'histoire du théâtre d\'Arras forme un des principaux chapitres des annales littéraires de cette ville\".\r\nElle marque aussi l\'un des tournants majeurs de l\'histoire même du théâtre occidental.\r\nAu XIIe siècle, le genre dramatique s\'émancipe avec ses jongleurs, ses ménestrels, ses trouvères...\r\nArras, alors ville prospère aux 80 poètes, n\'y est pas étrangère avec des auteurs comme Baude Fastoul, Jehan Bodel ou encore Adam de la Halle.\r\nVous vous apprêtez à participer à un jeu de piste en centre ville. Chaque bonne réponse vous mènera vers un nouveau site.\r\nPrêt à entrer dans l\'histoire ?\r\nAlors… En scène !',1,'68cf49a99181b_credits.webp'),(2,2,'Scène I','Paris, 1950. Vous êtes comédien, un festival d\'art dramatique est programmé à Arras dans quelques mois pour le VIIe centenaire d\'Adam de la Halle.\r\nVous candidatez auprès de comité d\'organisation.\r\nCe festival pourrait être le tremplin pour lancer votre carrière !\r\nQuelques semaines passent, vous n\'y croyez plus.\r\nLorsqu\'un matin, dans votre courrier...',1,'68cf49dab1768_prologue.webp'),(3,2,'Scène II','Arras, le jeudi 4 Janvier 1951.\r\nPresque deux heures de train pour venir de Paris, et il vous reste moins d’une demi-heure pour trouver la rue des Jongleurs dans cette ville que vous découvrez dans la brume glaciale d’un matin de janvier.\r\n“Un plan, chercher un plan… Pas de plan ?! Bon, eh bien nous ferons sans !”\r\nVous traversez la place du Maréchal Foch et choisissez de prendre l’axe principal, la rue Gambetta.\r\nVotre pas s’accélère, vos pensées vagabondent, votre regard traverse le café de la Poste. Un homme termine son café en feuilletant un magazine: A la une du Soir illustré, Jean Marais.\r\nVous l’aviez admiré dans la Belle et la Bête, vous irez voir son dernier film, le Château de verre. Il donne la réplique à Michèle Morgan :\r\n“- Madame, Bonjour ! Excusez-moi, je cherche la rue des jongleurs.”\r\n- Alors… La rue des jongleurs… Vous passez devant la Poste. Continuez jusqu’à la Banque de France. Là vous tournez à droite dans la rue Désiré Delansorne. Puis première à gauche. Au bout, vous serez dans la Rue des Jongleurs. Il y a l’Hôtel de Guines dans cette rue: Vous ne pouvez pas le rater !\r\n- Merci ! Bonne journée, Madame.”\r\nTrès vite, vous descendez l’axe principal du centre-ville, et arrivez au lieu de rendez-vous.\r\nRendez-vous au lieu de la convocation !',2,''),(4,3,'Scène I','Toute petite, cette rue des jongleurs est bien vide pour une journée d’audition !\r\nUne porte cochère: C’est l’Hôtel de Guines sur lequel figurent quatre plaques: associations des prisonniers de guerre, des déportés du travail, Rhin et Danube et Fédération nationale des déportés internés, résistants et patriotes…\r\nMais personne pour une quelconque audition ! Les volets du café Arthur, juste à côté, se lèvent. Peut-être sauront-ils vous renseigner.\r\n“-Bonjour ! Qu’est-ce que je vous sers ?\r\n- Rien, Merci Monsieur. Avez-vous entendu parler d’auditions aujourd’hui, ici, rue des jongleurs ?\r\n- Des auditions ? Ici ? Je ne vois pas, non. Au théâtre peut-être ? C’est André Reybaz qui s’occupe de ça, non ? Monsieur ? Monsieur ? Vous m’entendez ?”\r\nA cet instant, la parole vous manque, le sol se défile, vos sens se brouillent. Dans l’agitation ambiante, vous perdez connaissance. Dans la chute, votre tête heurte le sol. Le néant…',1,'68cf4b252ec4f_hotel_de_guines.webp'),(5,3,'Scène II','“-Hep là, il ne faut pas dormir ici, jeune homme! C’est jour de marché aujourd’hui ! Allez debout !”\r\nEffectivement, l’agitation bat son plein autour de vous: des cris, des étals, des odeurs. Et quelles odeurs ! Encore endolori de votre chute, vous levez les yeux sur la place du Théâtre. Plus de Théâtre, plus de café Arthur, plus d’Hôtel de Guines... Un marché aux poissons anime la Place.\r\n“- On est bien sur la place du Théâtre, n’est-ce-pas ?\r\n- La place du Châtelain, vous voulez dire ??! Vous êtes jongleur, vous ?\r\n- Jongleur ?\r\n- Vous préférez “ménestrel”, “trouvère”, “troubadour” ? Dites-moi, vous êtes bien venu réciter vos vers ? Une chanson ? Des fabliaux, peut-être ? De quel instrument jouez-vous ? \r\n- Euh, pas tout à fait ! Je suis venu de Paris pour le festival d’Adam de la Halle! On est bien le Jeudi 4 j… ?\r\n- Le jeudi 4 juin de l’année 1276, Monsieur. Moi aussi, je viens de Paris ! Eude de la Courroirie, clerc du Comte Robert II d’Artois. Quant à Maître Adam, il est fort occupé ! Le Bossu prépare la grande représentation de ce soir: Le Jeu de la Feuillée. Voyez comme Adam écrit. Vous êtes nouveau membre de la charité Notre-Dame, n’est-ce pas ?\r\nCe soir ? Le jeudi 4 juin 1276 ?\r\nQuelle charité ? Excusez-moi, mais vous êtes là pour l’audition aussi ?\r\n- La Charité de Notre-Dame des Ardents, enfin ! En l’an 1105, une terrible maladie, un feu ardent, a décimé la population arrageoise. La Sainte Vierge confia à deux jongleurs, Norman et Itier, un cierge qui guérit les malades. \r\nLa Sainte Chandelle et sa chapelle ont été érigées sur le petit marché en mémoire du Miracle des Ardents et qui, mieux qu’une confrérie de jongleurs, pourraient célébrer ce miracle sur la place publique, dites-moi ?\r\nVous n’êtes donc pas poète pour ignorer cela ?!\r\n- Enfin ! vous comprenez... Je ne suis pas poète et j’ignore tout de ce miracle ! Alors si on pouvait revenir à l’audition pour le septième centenaire d’Adam de la Halle, je préférerais !\r\n- Le septième centenaire ? Vous me semblez très confus, cher confrère ! Allez donc au Petit Marché ! Vous pourrez y admirer la Pyramide de la Sainte-Chandelle, les halles aux draps et les Maisiaux. Vous y trouverez Adam, il est loin d’avoir 700 ans !  A tantôt mon ami !”\r\nVous ne voyez déjà plus que les talons de cet homme pour le moins atypique, que vous réalisez:\r\nLe Petit Marché, je veux bien, mais où est-il ? Une pyramide a-t-il dit, Je devrais trouver…',2,'68cf4b431eab3_acte_1_photo.webp'),(6,3,'Scène III','Heureusement, vous aviez pris la bonne direction en cherchant à rejoindre Eude de la Courroirie. A votre gauche, une énorme bâtisse et une église se dessinent : l\'abbaye Saint Vaast ne ressemble pas à ça dans les guides Ravet-Anceau ?!!\r\nVous décidez de suivre quelques badauds. Le spectacle d’Adam de la Halle de ce soir va attirer les foules, c’est certain, et si le Petit Marché est bien le coeur de la ville, il ne sera pas difficile à trouver.\r\nEncore quelques pas et vous découvrez la place, cette fameuse chapelle des Ardents, qui n’a rien d’une pyramide, ces halles aux draps qui évoquent plutôt… L\'hôtel de ville ? Où est le lion du beffroi ?!\r\nVous remontez votre col, il fait plutôt frais pour un mois de juin.\r\nVotre retard à l’audition, désormais évident, vous agace. Les aiguilles de votre montre restent figées à 8h53.\r\n“- C’est fichu pour l’audition… Quelle poisse ! Ou c’est une très mauvaise blague ! Quelle heure est-il ? Pffff…” Maugréez-vous.',3,''),(7,4,'Scène I','Un passant vous interpelle :\r\n\"- Eh bien l’ami ? Tu sembles bien pensif pour un jour de fête ! Allez, viens donc profiter de la semaine grasse ! L\'abbé ne devrait plus tarder maintenant. Et après le spectacle, le banquet !\r\n- Je n’en ai que faire de votre abbé ! C’est Adam que je cherche !\r\n- Tu n\'es pas pensif, plutôt désagréable, je trouve !\r\n- Oui, pardonnez-moi… Je viens à Arras pour une audition. J’ai perdu connaissance et depuis j’ai l’impression d’être dans une énorme farce ! C’est incohérent !\r\n- Tu es tout pardonné… Même si je dois t’avouer: Ce sont tes propos qui me paraissent incohérents ! Bon ! Qui dois-tu voir ici ?\r\n- Je ne sais plus trop, mais j’ai rencontré le clerc du comte qui m’a dit de venir ici à la rencontre d’Adam de la Halle !\r\n- Aaaahhh ! Adam ? Sacré costume, cette année ! Vas donc voir là-bas : Il doit se préparer pour la représentation !\r\n- Aaahh ! Donc il y a bien une représentation ? On est bien le 4 Juin 1276, n’est-ce pas ? Au fait, il n’y avait pas une horloge sur ce beffroi ?\r\n- La semaine grasse en juin ? Tu divagues ? Nous sommes en mars de l’année 1541 ! Une horloge ? C’est que Jacques Halot est bien difficile à convaincre… Allez ! Bon vent l\'ami !”\r\nEnfin! Vous avez trouvé: un étendard rouge aux armes de l\'abbaye flotte devant l’hôtel de ville, une foule se presse autour d’un homme à la crosse d’argent doré, escorté de pages et de laquais.\r\nA proximité, un héraut en cotte d\'armes de damas violet somme tambours et trompettes de faire silence pour sa déclaration.',1,'68cf4bf719269_acte_2_question.webp'),(8,4,'Scène II','“- C’est Adam de la Halle ? ” demandez-vous à votre voisin.\r\n“- Que nenni ! C’est l’abbé de Liesse ! Ou l’abbé de la joie, si vous préférez. Et pour ma part, j’ai beau m’appeler Adam, je ne suis pas poète ! Toutes les confréries se rassemblent sur la place en ce jour du lundi gras, c’est banquet après le spectacle. Avec ses suppôts déguisés en moines, il mène les festivités une année durant.\r\n- Toutes les confréries se rassemblent ici ? Vraiment ? Celle de Notre Dame-des-Ardents aussi, donc ?\r\n- Probablement, leur domaine est juste à côté ! Vous voyez là ? C’est le préau des ardents. La salle des assemblées est si grande qu’on peut y voir des pièces de théâtre et même jouer au jeu de paume.\r\n- Et il y a une troupe en ce moment ?\r\n- Probablement… Allez donc voir ! Lorsque vous ferez face à l’hôtel de ville, contournez le sur la droite, faites quelques mètres et vous verrez le Préau des Ardents sur votre droite.',2,'68cf4c144e87d_abbe_de_liesse_photo.webp'),(9,5,'Scène I','Vous levez les yeux. Comme ces masques sont expressifs !  Ils  vous rappellent ceux du théâtre antique. Planté à l’angle de ce qui fut le préau des Ardents, vous tentez de saisir le terme de la discussion entre un homme et une jeune femme. Ce Monsieur Watelet semble plus que nerveux.\r\n“ - Je vous abandonne, Mademoiselle Mercier. Justement, je pars defendre mes intérêts. Prenez garde aux locaux: Votre avarice m’empêche même de les assurer ! Et portes closes à 20 heures précises.\r\n- Mon pauvre Monsieur Watelet… Aucune inquiétude, voyons ! Vos amis échevins y nuiront bien avant notre troupe ! Enfin, vous pouvez être assuré que nous entretiendrons vos rapports de bon voisinage !\r\n- à défaut de bon payeur, Mademoiselle !” Clame le propriétaire de la salle de spectacle en tournant les talons, un dossier sous le bras.\r\n“ - Aaah, ces provinciaux…” s’écrie-t-elle en hochant la tête, les bras au ciel.\r\n“- Je viens de Paris, Mademoiselle !” Déclarez-vous surgissant de nulle-part.\r\n“ - Ooooohhhh ! Sacrebleu ! Vous m’avez fait peur ! Êtes-vous tombé sur la tête jeune homme?\r\n - Vous ne croyez pas mieux dire, Mademoiselle. C’était le directeur du Festival ?” Répondez-vous en grimaçant.',1,'68cf537011d14_desire_bras.webp'),(10,5,'Scène II','- Sieur Watelet ? Nooon ! C’est le propriétaire des lieux !  Tout le monde le sait ici ! C’est la passion de l’argent et non celle du théâtre qui fait battre le coeur de ce bourgeois. Et c’est bien pour ça qu’il presse le pas! Il n’en dit mot, mais il doit aujourd’hui démontrer au magistrat qu’une salle de spectacle est inutile à Arras, sans omettre d’aborder l’immense préjudice qu’il aurait à subir si un théâtre venait à sortir de terre, à l’évidence !\r\n- Pardonnez-moi, Mademoiselle, mais j’ai bien vu un théâtre en arrivant à Arras !\r\n- En êtes-vous bien sûr mon garçon ?! Croyez-vous que ma troupe se contenterait de cet ancien jeu de paume, si la ville était dotée d’un théâtre ? De la raison, voyons ! Allez, au travail !  Nous allons prendre du retard: Le Magistrat de la ville a décidé que les représentations auraient désormais lieu entre 17h00 et 20h00 ! C’est dire si la rue était animée tantôt. Le voisinage a eu raison de l’art dramatique et du divertissement en cette ville…” soupire la jeune femme au charme enchanteur. Cependant chaque syllabe de “l’art dramatique” résonne comme un antidote.\r\nCette fois, avant de vous ridiculiser en évoquant le festival, vous vous assurez de la date:\r\n“- Excusez- moi, Mademoiselle, mais quel jour sommes-nous ?\r\n- Le vendredi 1er mars 1754: C’est aujourd’hui que Monsieur Watelet dissuadera les échevins de construire un vrai théâtre. Je vous l’ai dit, voyons ! N’avez vous donc rien compris ?”\r\nPlus de théâtre ? Plus de festival ! Plus de carrière ?! Il faut arrêter le Sieur Watelet ! Il en va de ma carrière ! Réalisez-vous.\r\nVous dévalez la rue à sa poursuite.\r\nVous reconnaissez l’abbaye Saint-Vaast, instinctivement, vous tournez à gauche.\r\nC’est certain : Le théâtre est par là !\r\nVous n’avez pas pu rêver ! \r\nVous l’avez vu ce matin avant de tomber au café Arthur !\r\nVous devez vérifier !',2,'68cf53ab06382_acte_4_indice.webp'),(11,6,'Scène I','Les mains sur les genoux, vous reprenez péniblement votre souffle. Vous fermez les yeux, vous secouez la tête: quelle journée fantasmagorique…\r\n Une tape sur l’épaule vous extirpe de vos pensées :\r\n“- Bon alors ? Tu fais quoi ? Tu entres ou pas ?\r\n- Pardon ?”\r\nLa pétulance du jeune homme trouble la quiétude de la place.\r\n“- Tu viens au théâtre ? Allez ! Viens vite ! Ça va bientôt commencer !”\r\nVous levez alors des yeux écarquillés sur une sobre façade de calcaire blanc:\r\n“- Le théâtre ? C’est le théâtre ? Monsieur Watelet a donc échoué !?\r\n- Probablement ! Tu en sembles convaincu ?! Un magnifique théâtre à l’italienne, non ? Dire qu’il y avait là d’anciennes prisons, un corps de garde, la bourse commune des pauvres et les minques !\r\n- Ne m’en parle pas : Cette odeur de poisson, je m’en souviens ! En quoi est-ce un théâtre à l’italienne ? Je ne vois pas ce que ce théâtre a d’italien !\r\nDerrière cette façade néo-classique, se cache une étonnante salle en ovale tronqué au décor chatoyant dans le style Louis XVI avec trois étages de balcons et le poulailler. La scène est surélevée, vois-tu ? Son plancher, légèrement incliné vers le parterre, permet au public d\'apprécier le jeu des artistes, comme l’interprétation des œuvres musicales d’ailleurs !  Et la cage de scène est impressionnante d’ingéniosité pour changer les décors. Il était grand temps pour la ville d’Arras de se doter d’un théâtre ! En plus, un espace est prévu pour accueillir le Quand on pense à l’état du jeu de Paume et à son propriétaire si cupide… Pfff.\r\n- Justement, je voul…\r\n- Savais-tu qu’un professeur de mathématiques, Adrien Gillet, aurait été sollicité pour un projet de théâtre en novembre 1782 ? Ceci dit, lorsqu’on connaît le montant des émoluments versés à l’architecte Laurent Guislain Joseph Linque, quelque chose comme 17000 livres je crois, cela ne laisse guère de doute sur la paternité du théâtre arrageois, non ?\r\nJ’ai même vu sa signature sur l’un des plans, un coupe transversale de la salle au niveau de la fosse d’orchestre, tu vois ? Impressionnant…\r\n- Oui, je vois, je vois… La dame là-bas ?\r\n- Ah ! C’est Madame de Boiry ! Elle y a perdu son garde-manger dans ce théâtre !\r\nMadame de Boiry avait une propriété située à l’arrière du théâtre.\r\nLa ville a d’abord racheté le bâtiment de la Bourse commune à raison d’une rente annuelle de 200 livres en argent, je crois.\r\nEn avril 1783, c’est le garde-manger de Madame de Boiry qui entre en scène ! Enfin, si tu me permets l’expression ? Je te fatigue avec tout ça, non ?”',1,'68cf54871151f_acte_5.webp'),(12,6,'Scène II','Vous restez muet, mais votre regard fuyant suffit. Vous scrutez cette place que vous connaissez. Encore que… Elle vous semble différente.\r\nVotre compagnon reprend de plus belle:\r\n“- Oh ! Regarde ! Messieurs Garnier et Durand ! La troupe arrive: ça va bientôt commencer ! Comme j’ai hâte !\r\n- Attends-moi ! Que se passe-t-il ?”\r\nLe jeune homme ouvre et vous tend un petit livre tout juste sorti de sa poche:\r\n“- L’inauguration de la nouvelle salle de spectacle eut lieu le mercredi 30 Novembre 1785, sans cérémonie ni apparat en présence des membres du magistrat. C’est écrit là !”\r\nLa page de titre indique:\r\nLe Théâtre à Arras avant et après la Révolution.\r\nPar Adolphe de Cardevacque.\r\n- Arras: Typographie de Sède, 1884.\r\n1884 ??? Le moteur d’une Renault 4CV démarre. Vous réalisez aussitôt:\r\n“- Une 4CV, en 1785 ?\r\n- On est en 1951 ! 1785... Quelle idée ! L’inauguration du théâtre n’est qu’une reconstitution !\r\n- 1951… Dis, tu aurais entendu parler d’audition pour le festival du VIIe Centenaire d’Adam de la Halle ?\r\n- André Reybaz doit encore être à l’abbaye Saint-Vaast à cette heure. Vas voir ! Tu passes devant l\'Hôtel de Guines et à gauche toute !”\r\nVous le remerciez d’une chaleureuse poignée de main avant de rebrousser chemin.',2,'68cf54ab57da0_acte_4_question.webp'),(13,7,'','Plus que quelques mètres et vous connaîtrez le verdict. Par la grande porte entrebâillée de l’abbaye Saint-Vaast, vous apercevez le jeu d’une troupe de comédiens costumés. Aucun doute, cette fois, ce sont des comédiens, plus de voyages dans le temps: Les uns chantent, les autres alternent grands battements et pirouettes pendant qu’un petit comité joue et rejoue la même scène avec emphase.\r\n“- Allez-y, avancez, jeune homme, André vous attend, là-bas, devant l’entrée du Musée des Beaux-Arts.\r\n“- Ah André ? Monsieur André Reybaz ?” demandez-vous. Deux messieurs en costume cravate observent les répétitions. Comme l’un d’eux vous serre la main, l’angoisse vous serre la poitrine. Si le metteur en scène du festival vous attend, c’est que l’audition, c’est pour…\r\nMAINTENANT !!!\r\n“- Ooohhh, laissez le donc avancer, voyons ! C’est qu’il vient de bien loin pour participer à l’audition.” Vient vers vous, un homme élégant aux cheveux bruns. Une charmante jeune femme lui emboîte le pas. Il vous tend la main :\r\n“- Bonjour ! Vous voici donc! Heureux de vous rencontrer. Je vous présente Messieurs René Durant, secrétaire de la Chambre de Commerce, et le Docteur Baudry, commissaire délégué du comité. Enfin, Catherine Toth, ma compagne. Et moi, pardon ! André Reybaz, heureux de faire enfin votre connaissance. Alors ? Ce voyage pour arriver jusqu’ici ? Pas trop éprouvant ?\r\n- Nooon !” Répondez-vous en vous frottant la tête, en signe d’hésitation.\r\n“- Vous avez apprécié ?\r\n- La répétition ? oui, bien sûr, Monsieur Reybaz ! Comme je serais honoré de monter sur scène auprès de vous.\r\n- Quel jour sommes-nous ?\r\n- Euh, le 4 janvier, Monsieur Reybaz. Enfin, je crois ! Le 4 janvier 1951.\r\n- Saviez-vous que la pièce que nous répétons aujourd’hui, Le Sicilien ou l’Amour peintre de Molière a été représentée pour la première fois, le 5 Janvier 1667 à Saint-Germain en Laye ? Certains y verraient un signe du destin.\r\n- Je l’ignorais, Monsieur Reybaz.” Répondez-vous embarrassé.\r\nAccompagné d’un geste amical sur l’épaule, André Reybaz vous invite à entrer au Musée :\r\n“- Alors ? On la fait cette audition ?! Il ne manquait plus que vous !\"',1,'68cf558c8772b_epilogue_photo.webp');
/*!40000 ALTER TABLE `chapitres` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `etapes`
--

DROP TABLE IF EXISTS `etapes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `etapes` (
  `id_etape` int(11) NOT NULL AUTO_INCREMENT,
  `id_parcours` int(11) NOT NULL,
  `titre_etape` varchar(255) NOT NULL,
  `mp3_etape` varchar(255) DEFAULT NULL,
  `image_header` varchar(255) DEFAULT NULL,
  `image_question` varchar(255) DEFAULT NULL,
  `indice_etape_texte` text DEFAULT NULL,
  `indice_etape_image` varchar(255) DEFAULT NULL,
  `question_etape` text DEFAULT NULL,
  `reponse_attendue` varchar(255) DEFAULT NULL,
  `latitude` decimal(9,6) DEFAULT NULL,
  `longitude` decimal(9,6) DEFAULT NULL,
  `ordre_etape` int(11) DEFAULT NULL,
  `type_validation` enum('reponse','reponse+geo') NOT NULL DEFAULT 'reponse',
  PRIMARY KEY (`id_etape`),
  KEY `id_parcours` (`id_parcours`),
  CONSTRAINT `etapes_ibfk_1` FOREIGN KEY (`id_parcours`) REFERENCES `parcours` (`id_parcours`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `etapes`
--

LOCK TABLES `etapes` WRITE;
/*!40000 ALTER TABLE `etapes` DISABLE KEYS */;
INSERT INTO `etapes` VALUES (1,1,'Prologue','68cf48b8c9af2_acte_0_prologue.mp3','68cf48b8cd0c4_credits.webp','','','','','',NULL,NULL,1,'reponse'),(2,1,'La convocation','68cf49799f48c_acte_1.mp3','68cf4979b9eee_prologue.webp','68cf4979bd0e4_prologue.webp','L\'hôtel particulier de la famille De Guînes, reprend le modèle de l’hôtel parisien bâti entre cour et jardin. Si les portes sont ouvertes, vous pourrez admirer les visages mutins de la façade et sa cour pavée. Dirigez-vous vers l\'hôtel et trouvez sa plaque pour répondre à la prochaine question. A tout de suite !','68cf4979b1a0d_hotel_de_guines_indice.webp','Quelle est l\'année de construction de l\'Hôtel de Guînes ?','1738',50.291093,2.773966,2,'reponse'),(3,1,'Au café Arthur','68cf4af786e90_acte_2.mp3','68cf4af7b5fa2_hotel_de_guines.webp','68cf4af7bb58a_acte_2_question.webp','Le marché y est fréquenté les mercredis et samedis, La place porte le nom “des Héros” depuis 1945. La Sainte Chandelle est représentée sur une plaque scellée au sol, les fondations sont pavées de bleu. Dirigez vous vers cette place, trouvez cette plaque, pour répondre à la prochaine question. A tout de suite !','68cf4af7b0627_acte_2_indice.webp','En quelle année la chapelle de la Sainte Chandelle fut-elle détruite ?','1791',50.291093,2.773966,3,'reponse'),(4,1,'Jour de Fête','68cf4bd34b4bd_acte_3.mp3','68cf4bd36c3a7_acte_2_question.webp','68cf4bd370c1f_acte_3_question.webp','Lorsque le préau des ardents servait de jeu de paume, la noblesse et la bourgeoisie y perdirent fortune. La rue qui traversait le domaine des ardents fut alors désignée comme la “rue du tripot”. Elle a aujourd’hui repris son nom d’origine, rue Neuve des Ardents. Mais ne vous bornez pas à emprunter cette rue: Vers l’Ancienne Comédie vous emmener, nous aurions DÉSIRÉ. Levez les yeux et observez les masques pour avancer. A tout de suite !','68cf4bd367de7_indice acte 3.webp','Combien de masques décorent la façade de la maison aux volets bleus ?','7',50.292213,2.775992,4,'reponse'),(5,1,'La Troupe','68cf4cd8ea23c_acte_4.mp3','68cf4cd90ddf1_acte_3_question.webp','68cf4cd910a5e_acte_4_question.webp','Un retour aux sources s’impose. le Café Arthur, la chute, les jongleurs… Le théâtre, bien sûr, le théâtre... A tout de suite !','68cf4cd9094bd_theatre_indice.webp','En quelle année débutent les travaux de construction du théâtre ?','1780',50.292213,2.775992,5,'reponse'),(6,1,'L\'Audition','68cf545029615_acte_5.mp3','68cf545051c46_acte_4_question.webp','68cf5450548a9_pierre_paquet.webp','La compagnie théâtrale de Catherine Toth et André Reybaz ont investi la cour de l\'Abbaye Saint-Vaast pour interpréter la pièce de Molière \"Le Sicilien ou l\'Amour peintre\" le samedi 30 juin et le dimanche 1er juillet 1951. A tout de suite !','68cf5450486ad_pierre_paquet_indice.webp','Qui a reconstruit l\'Abbaye Saint-Vaast après la 1ère Guerre mondiale ?','Pierre PAQUET',50.291662,2.773263,6,'reponse'),(7,1,'Epilogue','68cf54ffd4613_acte_6_epilogue.mp3','68cf54ffe306d_epilogue_photo.webp','','','','','',NULL,NULL,7,'reponse');
/*!40000 ALTER TABLE `etapes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parcours`
--

DROP TABLE IF EXISTS `parcours`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `parcours` (
  `id_parcours` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `nom_parcours` varchar(100) NOT NULL,
  `description_parcours` text DEFAULT NULL,
  `image_parcours` varchar(255) DEFAULT NULL,
  `statut` tinyint(1) DEFAULT 0,
  `mode_geo_parcours` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_parcours`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `parcours_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users_admins` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parcours`
--

LOCK TABLES `parcours` WRITE;
/*!40000 ALTER TABLE `parcours` DISABLE KEYS */;
INSERT INTO `parcours` VALUES (1,1,'Le théâtre','Plongez au cœur du patrimoine théâtral d’Arras à travers un parcours immersif. Découvrez l’histoire du théâtre, ses grandes figures, ses anecdotes et ses coulisses. Chaque étape vous invite à explorer les lieux emblématiques, à résoudre des énigmes et à revivre les moments forts qui ont marqué la scène arrageoise. Ce parcours est idéal pour les passionnés d’art, d’histoire et de spectacle vivant.','68cf47e641e51_credits.webp',1,1);
/*!40000 ALTER TABLE `parcours` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parcours_personnages`
--

DROP TABLE IF EXISTS `parcours_personnages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `parcours_personnages` (
  `id_parcours` int(11) NOT NULL,
  `id_personnage` int(11) NOT NULL,
  PRIMARY KEY (`id_parcours`,`id_personnage`),
  KEY `id_personnage` (`id_personnage`),
  CONSTRAINT `parcours_personnages_ibfk_1` FOREIGN KEY (`id_parcours`) REFERENCES `parcours` (`id_parcours`) ON DELETE CASCADE,
  CONSTRAINT `parcours_personnages_ibfk_2` FOREIGN KEY (`id_personnage`) REFERENCES `personnages` (`id_personnage`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parcours_personnages`
--

LOCK TABLES `parcours_personnages` WRITE;
/*!40000 ALTER TABLE `parcours_personnages` DISABLE KEYS */;
INSERT INTO `parcours_personnages` VALUES (1,1),(1,2),(1,4),(1,5),(1,6),(1,7),(1,8),(1,9);
/*!40000 ALTER TABLE `parcours_personnages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personnages`
--

DROP TABLE IF EXISTS `personnages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personnages` (
  `id_personnage` int(11) NOT NULL AUTO_INCREMENT,
  `nom_personnage` varchar(255) NOT NULL,
  `image_personnage` varchar(255) DEFAULT NULL,
  `mp3_personnage` varchar(255) DEFAULT NULL,
  `description_personnage` text DEFAULT NULL,
  PRIMARY KEY (`id_personnage`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personnages`
--

LOCK TABLES `personnages` WRITE;
/*!40000 ALTER TABLE `personnages` DISABLE KEYS */;
INSERT INTO `personnages` VALUES (1,'Adam de la Halle','68cf59b38dd8c_adam_de_la_halle (1).webp','68cf59b39311f_adam_de_la_halle.mp3','Né à Arras vers 1240, mort vers 1287 à Naples (Italie).\r\n\r\nAdam de la Halle est un trouvère de langue picarde, jongleur et musicien. Il est né à Arras et est le fils d’Henri le Bossu d’où son surnom.\r\n\r\nIl étudie à l’Université de Paris où il devient « Maître des Arts ».\r\n\r\nAdam de la Halle est l’auteur du Jeu de la Feuillée et du Jeu de Robin et Marion. Il est sans doute selon ses biographes, le meilleur auteur comique de son siècle. Le Jeu de Robin et Marion constitue certainement le plus ancien exemple de théâtre d’inspiration entièrement profane.\r\n\r\nAdam de la Halle fait partie de la suite du comte d’Artois, envoyé par le roi de France, au secours de Charles d’Anjou, conquérant de la Sicile en 1266, et réfugié à Naples.\r\n\r\nIl est probablement mort à Naples aux environs de 1287.\r\n\r\nL\'autel latéral droit de cette église est l\'ancien autel de la chapelle ND des Ardents qui se trouvait jusqu\'en 1789 sur la Petite Place. Le cierge reliquaire qui y était vénéré était placé sous la surveillance de la confrérie des Trouvères dont Adam de la Halle fut l\'un des membres et le Mayeur.'),(2,'Les Ardents','68cf5b93bda90_les_ardents.webp','68cf5b93c06ad_les_ardents.mp3','En l’an 1105, une terrible maladie, qui brûle les victimes dans d’atroces douleurs, décime la population arrageoise. Outre la douleur, les sensations de brûlures très fortes, accompagnées de convulsions, voire de crises de folie hystérique sont les symptômes les plus courants. Certains malades voient leurs membres, se gangrener, devenir noirs et secs. Mains et pieds finissent par se détacher des articulations.\r\n\r\nTrès vite, la population voit en ce feu ardent, une punition divine ou un déchaînement diabolique et destine ses prières à la Sainte-Vierge pour sa miséricorde.\r\n\r\nEn ce temps, un jongleur du château de Saint-Pol-Sur-Ternoise, nommé Norman, tue le frère d’un jongleur du Brabant, Itier. Alors qu’une haine mortelle anime les deux hommes, la Vierge leur apparaît en rêve la même nuit les enjoignant de se rendre à la Cathédrale pour s’y réconcilier en présence de l\'évêque d’Arras, Lambert de Guines.\r\n\r\nLes deux jongleurs s\'exécutent, incapables de faire abstraction de leur inimitié. Cependant, l’évêque parvient à obtenir leur pardon, en leur démontrant que la réconciliation était le seul moyen d’accomplir la volonté divine, et de guérir les malades de ce “feu d’enfer”.\r\n\r\nLe miracle s’accomplit lorsqu’après une nuit de prière dans la Cathédrale, Marie se présente aux trois hommes, un cierge à la main. La cire dissoute dans l’eau guérit les malades.\r\n\r\nLe Mal des Ardents n’est identifié définitivement qu’au XIXe siècle: Il s’agit d’un petit champignon parasite du seigle qui prolifère par temps humide. Moulu avec le reste des grains, il touche surtout les petites gens, premiers consommateurs de pain noir.\r\n\r\nPeu après le miracle, une confrérie pieuse est créée dans le but de rendre un culte régulier à la relique. Les jongleurs Itier et Norman en seraient les premiers mayeurs. La Confrérie des Ardents est un moteur essentiel de la vie culturelle et littéraire d’Arras des XIIe et XIIIe siècles.\r\n\r\nEn 1215, Marguerite de Flandre fait don à la confrérie d’un reliquaire en argent niellé et filigrané, reprenant la forme du cierge et décide de faire édifier une chapelle sur la Petite Place jouxtant le clocher en forme de cierge élevé auparavant par la confrérie.\r\n\r\nL’ensemble architectural est abattu en 1791. En mémoire de cette chapelle, l\'architecte Alexandre Grigny s’inspire du Saint Cierge pour le clocher de la chapelle des Ursulines d’Arras. L’église Notre Dame des Ardents abrite depuis 1876 la confrérie et ses éléments de culte. Les Archives de l’ancienne confrérie de Notre-Dame des Ardents, dissoute le 18 mars 1792, ont, quant à elles, été déposées à la Médiathèque par le docteur Georges Paris en 1965.\r\n\r\n'),(4,'L\'Abbé de Liesse','68cf5c4e48f01_abbe_de_liesse_2.webp','68cf5c4e4d4f7_abbe_de_liesse.mp3','A partir de 1430, les mémoriaux de la ville d’Arras font mention de l’engouement des magistrats et des habitants pour les spectacles publics. Mais dès le milieu du XIVe siècle, de joyeuses bandes se créent, leurs chefs sont nommés “Roi des Hours”, “Prince de Saint-Jacques” ou encore “Prince d’Amour”.\r\n\r\nÉlu chaque année par les officiers du Duc de Bourgogne, Comte d’Artois, par le magistrat et la bourgeoisie, l’Abbé de Liesse est un directeur atypique chargé de mener une véritable troupe d’acteurs et de présider les festivités. Il reçoit, outre sa charge d’amuseur public, une crosse d’argent doré, symbole distinctif de sa dignité, qu’il remet à la fin de son exercice.\r\n\r\nAvec sa troupe de suppôts ou de baladins, les bien-nommés “moines”, l’abbé de Liesse anime la place publique, tout particulièrement le Dimanche gras. De fait, les festivités contribuent à la prospérité du territoire, comme à l’union des villes limitrophes.\r\n\r\nLa ville attribue d’ailleurs à l’Abbé de Liesse, les fonctions d’ambassadeur. Sous escorte de pages et de laquais, il entame alors, à la charge de la ville, une tournée des festivals alentour.\r\n\r\nLes dernières mentions de ce personnage dans les registres synodaux datent de 1541. Parmi les représentations des grands moments de l’histoire arrageoise du cortège historique du 17 Juillet 1910, l’Abbé de Liesse foule à nouveau le pavé.'),(5,'Désiré Bras','68cf5ccc92df6_desire_bras.webp','68cf5ccc979fd_desire_bras.mp3','Alors chef de l’état civil, Désiré Bras sauve de l’incendie les registres lors du bombardement de 1914. Une rue arrageoise porte son nom. Il s’agit d’ailleurs de l’une des rares rues de la ville dont on peut lire l’ancien et le nouveau nom : Rue Désiré Bras ou ancienne rue du blanc pignon.\r\n\r\nL’étymologie de ce nom semble aisée: une muraille blanchie, probablement.\r\n\r\nCependant, cette rue était auparavant désignée sous le nom d’une supposée enseigne: celle du Blanc-Pigeon ou du Blanc-Coulon. Selon cet usage, la rue s’appelait initialement la rue de l’Ancienne Comédie: S’y trouvait alors la seule salle de spectacle avant la construction du remarquable théâtre à l’italienne, achevé en 1785.'),(6,'Pierre Paquet','68cf5cf2b9982_pierre_paquet.webp','68cf5cf2be107_pierre_paquet.mp3','1875-1959\r\n\r\nPierre Paquet est nommé Architecte des Monuments historiques pour le Pas-de-Calais en 1905 et en chef en 1919. Il a aussi en charge l’Hôtel de Cluny, la Sainte Chapelle et le Mont-Saint-Michel.\r\n\r\nAu lendemain de la Première Guerre mondiale, Arras est détruite à 80%. En application de la loi de 1919 sur les dommages de guerre, imposant la reconstruction à l\'identique des monuments classés, Pierre Paquet dirige avec l\'architecte Paul Decaux la reconstruction de l’Hôtel de ville, du Beffroi, de l’ensemble des maisons entourant les deux places, de l’abbaye Saint-Vaast et de la cathédrale qui s’achève en 1934.\r\n\r\nIl réalise une œuvre respectueuse du modèle d’origine et novatrice restituant la parure monumentale d’Arras.'),(7,'Saint-Vaast','68cf5d34436ff_saint_vasst.webp','68cf5d34467a2_saint_vaast.mp3','Mort à Arras le 6 février 540.\r\n\r\nSaint Vaast est né dans le Sud-Ouest de la France et ordonné prêtre dans la région de Toul.\r\n\r\nAprès la victoire de Tolbiac (496), Saint Vaast est chargé, par l’évêque de Toul, saint Ursus, de catéchiser Clovis et de le conduire à Reims afin de recevoir le baptême par saint Remi avec 3 000 de ses soldats.\r\n\r\nVers 500, saint Remi envoie saint Vaast, comme évêque à Arras. Il s’installe dans la cité antique où il accomplit plusieurs miracles. Il chasse notamment un ours, caché sous l’autel de l’église qu’il souhaite relever et lui intime l’ordre de quitter la région, ce que l’ours devenu obéissant, fait immédiatement.\r\n\r\nSaint Vaast multiplie les établissements religieux dans la région jusqu’à son décès vers 540. Ses reliques, conservées à travers les siècles, sont encore aujourd’hui vénérées dans la Cathédrale d’Arras.'),(8,'Jehan Bodel','68cf5d662ddef_jehan_bodel_photo.webp','68cf5d6631337_jean_bodel.mp3','Né à Arras vers 1165, mort à Arras vers 1210-1215\r\n\r\nJehan ou Jean Bodel est un trouvère-ménestrel né à Arras dans la seconde moitié du XIIe siècle. Il écrit en ancien français des chansons de geste et des fabliaux.\r\n\r\nOn lui doit un classement des différents thèmes littéraires.\r\n\r\nEn 1202, il contracte la lèpre, maladie encore fort répandue. En raison de la contagion du mal, les lépreux sont exclus de la société. Jehan Bodel quitte Arras pour entrer dans une léproserie, située dans les faubourgs, à Méaulens ou à Beaurains, dans laquelle il meurt.\r\n\r\nMais avant de quitter le monde des vivants, Jehan Bodel, écrit des Congés où il fait ses adieux aux siens. C’est une poésie extrêmement personnelle sur sa mort à venir.'),(9,'André Reybaz','68cf5da0e8a83_andré _reybaz_photo.webp','68cf5da0eb6ba_andre_reybaz.mp3','André Reybaz est un acteur et un metteur en scène français.\r\n\r\nSi ses débuts au cinéma sont discrets, André Reybaz n’a qu’une vingtaine d’années lorsqu’il tourne auprès de grands noms du cinéma français comme Michel Simon, pour le film “Aux bonheurs des Dames” (1942), ou encore Ginette Leclercq dans “le Val d’enfer” (1943).\r\n\r\nDans l’immédiat après-guerre, il privilégie le théâtre et fonde, avec sa compagne Catherine Toth, la Compagnie du Myrmidon, qui reçoit le premier prix des jeunes compagnies en 1949 pour la direction, au théâtre Marigny, de Fastes d’Enfer de Michel de Ghelderode.\r\n\r\nEn 1951, le couple créé à Arras le Festival d’Art Dramatique. À l’occasion du VIIe centenaire d’Adam de la Halle. La programmation des printemps arrageois anime la ville avec spectacles, créations artistiques, concerts, récitals poétiques, veillées, expositions, conférences… Le festival est alors considéré comme le plus important après celui d’Avignon et contribue inéluctablement au rayonnement artistique de la ville.\r\n\r\nLa nomination d’André Reybaz, en 1960, marque les débuts du centre dramatique du Nord, qu’il dirige pendant 10 ans. La mise en scène du “Mal court” de Jacques Audiberti, avec Philippe Noiret et Suzanne Flon, en 1961 est l’un de ses plus grands succès.\r\n\r\nEn Février 2007, le théâtre d’Arras rouvre ses portes après 4 ans de travaux de restauration et de modernisation. Cet équipement culturel de premier plan inaugure une nouvelle salle, creusée sous la salle des concerts: la salle André Reybaz.');
/*!40000 ALTER TABLE `personnages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id_session` char(36) NOT NULL,
  `date_creation` timestamp NULL DEFAULT current_timestamp(),
  `derniere_etape_validee` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_session`),
  KEY `derniere_etape_validee` (`derniere_etape_validee`),
  CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`derniere_etape_validee`) REFERENCES `etapes` (`id_etape`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_admins`
--

DROP TABLE IF EXISTS `users_admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users_admins` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `nom_user` varchar(100) DEFAULT NULL,
  `email_user` varchar(255) NOT NULL,
  `mot_de_passe_user` varchar(255) NOT NULL,
  `role_user` varchar(50) NOT NULL DEFAULT 'admin',
  `date_creation_user` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `email_user` (`email_user`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_admins`
--

LOCK TABLES `users_admins` WRITE;
/*!40000 ALTER TABLE `users_admins` DISABLE KEYS */;
INSERT INTO `users_admins` VALUES (1,'Admin','admin@exemple.com','$2y$12$6mL8LYyKct1YZEAWmnsF5ew56CVcGGYpd.9bb67vWa7LO9yUopkXe','admin','2025-09-20 23:12:25');
/*!40000 ALTER TABLE `users_admins` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-09-21  4:10:17
