-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: austro_asian_times
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `advertisements`
--

DROP TABLE IF EXISTS `advertisements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `advertisements` (
  `ad_id` int(11) NOT NULL AUTO_INCREMENT,
  `image_url` varchar(255) NOT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `position` varchar(50) DEFAULT 'home_top',
  `start_date` datetime DEFAULT current_timestamp(),
  `end_date` datetime DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  PRIMARY KEY (`ad_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `advertisements`
--

LOCK TABLES `advertisements` WRITE;
/*!40000 ALTER TABLE `advertisements` DISABLE KEYS */;
/*!40000 ALTER TABLE `advertisements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `article_tags`
--

DROP TABLE IF EXISTS `article_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `article_tags` (
  `article_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`article_id`,`tag_id`),
  KEY `tag_id` (`tag_id`),
  CONSTRAINT `article_tags_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`article_id`) ON DELETE CASCADE,
  CONSTRAINT `article_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`tag_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `article_tags`
--

LOCK TABLES `article_tags` WRITE;
/*!40000 ALTER TABLE `article_tags` DISABLE KEYS */;
INSERT INTO `article_tags` VALUES (1,1),(3,1),(4,2),(4,3),(5,4),(5,5),(6,1),(8,8),(8,9),(9,10),(9,11),(9,12),(10,12),(10,13),(10,14),(10,15),(10,16),(10,17),(11,12),(11,16),(11,18),(11,19),(11,20),(11,21);
/*!40000 ALTER TABLE `article_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `articles` (
  `article_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `status` enum('draft','pending','approved','declined') DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `author_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`article_id`),
  KEY `author_id` (`author_id`),
  CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articles`
--

LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
INSERT INTO `articles` VALUES (1,'Website Development','Website development is the process of creating and maintaining websites. It\'s a broad term that encompasses a variety of tasks, from the initial planning and design to the actual coding and deployment of a website. Think of it as constructing a house: you need a blueprint, materials, and skilled builders to bring it to life. Similarly, website development requires a well-defined plan, the right technologies, and the expertise of developers.\r\n\r\nAt its core, website development involves two main aspects:\r\n\r\nFront-end development: This is what users see and interact with directly. It\'s the visual and interactive layer of the website. Front-end developers use languages like HTML (for structure), CSS (for styling), and JavaScript (for interactivity) to create the layout, design, and user experience. Imagine the walls, paint, furniture, and how you move around inside the house – that\'s the front-end.\r\n\r\nBack-end development: This is the engine that powers the website behind the scenes. It deals with the server, database, and application logic. Back-end developers use languages like Python, Java, PHP, Ruby, and databases like MySQL, PostgreSQL, and MongoDB to manage data, handle user requests, and ensure the website functions correctly. Think of the plumbing, electrical wiring, and foundation of the house – essential for its operation but not directly visible.','uploads/img_68262211b0914.jfif','approved','2025-05-15 17:19:13','2025-05-16 14:00:28',3),(3,'Machine Learning: The Intelligent Force','From personalized recommendations on your favorite streaming service to the sophisticated algorithms powering self-driving cars, machine learning (ML) has rapidly moved from the realm of science fiction to an integral part of our daily lives. This powerful branch of artificial intelligence is enabling computers to learn from data, identify patterns, and make decisions with increasing accuracy and autonomy, fundamentally reshaping industries and the way we interact with technology.\r\n\r\nAt its core, machine learning is about teaching computers without explicitly programming them for every single task. Instead, ML algorithms are fed vast amounts of data, allowing them to identify underlying structures, learn from examples, and improve their performance over time. Think of it like teaching a child – you don\'t provide a rule for every possible situation, but rather offer examples and feedback, allowing the child to develop their own understanding.\r\n\r\nThis data-driven approach has led to breakthroughs in numerous fields. In healthcare, ML algorithms are being used to analyze medical images for early disease detection, personalize treatment plans, and even accelerate drug discovery. The financial sector leverages ML for fraud detection, risk assessment, and algorithmic trading. Retail benefits from personalized marketing and optimized supply chains, while manufacturing employs ML for predictive maintenance and quality control.\r\n\r\nOne of the most visible applications lies in natural language processing (NLP), a subfield of ML that enables computers to understand and process human language. This powers virtual assistants like Siri and Alexa, improves machine translation, and enhances customer service through chatbots. Similarly, computer vision, another ML domain, allows machines to \"see\" and interpret images, driving advancements in facial recognition, autonomous vehicles, and robotic automation.\r\n\r\nHowever, the rise of machine learning also presents challenges. Concerns surrounding data privacy, algorithmic bias, and the potential impact on the job market are crucial considerations that require careful attention and ethical frameworks. Ensuring fairness, transparency, and accountability in ML systems is paramount to fostering public trust and maximizing the benefits of this technology for all.\r\n\r\nLooking ahead, the potential of machine learning appears limitless. As computational power continues to grow and data availability expands, we can expect even more sophisticated and impactful applications. From creating truly personalized learning experiences to tackling complex global challenges like climate change and disease, machine learning is poised to be a driving force of innovation in the years to come. Understanding its capabilities and limitations is no longer just for tech experts; it\'s becoming essential knowledge for navigating the increasingly intelligent world around us.','uploads/img_682744cb24c11.jpeg','approved','2025-05-16 13:59:39','2025-07-03 03:05:04',3),(4,'World War II: A Global Conflict That Reshaped the World','World War II, a global conflict that raged from 1939 to 1945, remains the deadliest war in human history, claiming the lives of an estimated 60 to 80 million people. It was a war fought on land, sea, and air, involving a vast majority of the world\'s countries, ultimately reshaping the geopolitical landscape and leaving an indelible mark on the 20th century and beyond.\r\n\r\nThe Seeds of War: The end of World War I left a legacy of unresolved issues and simmering tensions. The Treaty of Versailles, imposed on Germany, was perceived as harsh and unjust, fostering resentment and contributing to economic instability. The rise of aggressive ideologies like fascism in Italy under Mussolini and Nazism in Germany under Hitler, coupled with Japan\'s expansionist ambitions in Asia, further destabilized the international order. The failure of the League of Nations to effectively maintain peace also played a significant role.\r\n\r\n\r\n\r\n\r\nKey Events and Turning Points: The invasion of Poland by Nazi Germany on September 1, 1939, is widely considered the start of World War II. This act of aggression prompted Britain and France to declare war on Germany. The early years of the war saw Germany\'s Blitzkrieg tactics result in the swift conquest of much of Europe, including France. The Battle of Britain in 1940 marked a crucial turning point as the Royal Air Force successfully defended against German air attacks, preventing a planned invasion.\r\n\r\nThe war expanded dramatically in 1941 with Germany\'s invasion of the Soviet Union (Operation Barbarossa) and Japan\'s attack on Pearl Harbor, which brought the United States into the conflict. These events created two major theaters of war: the European theater, primarily pitting the Allied powers (including Britain, the Soviet Union, and the United States) against the Axis powers (Germany and Italy), and the Pacific theater, where the United States and its allies fought against Japan.\r\n\r\nSignificant battles and campaigns shaped the course of the war. In Europe, the Battle of Stalingrad marked a major turning point on the Eastern Front, halting the German advance and leading to their eventual retreat. The Allied invasion of Normandy on D-Day in June 1944 opened a crucial second front in Western Europe, leading to the liberation of France and the push towards Germany. In the Pacific, battles like Midway and Guadalcanal were pivotal in turning the tide against Japan.\r\n\r\nThe Holocaust and Other Atrocities: World War II was also marked by horrific atrocities, most notably the Holocaust, the systematic genocide of approximately six million European Jews by Nazi Germany and its collaborators. Other groups, including Roma, homosexuals, and disabled people, were also persecuted and murdered. In Asia, the Japanese military committed numerous war crimes, including the Nanking Massacre and the use of biological weapons.\r\n\r\n\r\nThe End of the War and Its Aftermath: The war in Europe concluded with the unconditional surrender of Germany in May 1945, following the Soviet capture of Berlin and Hitler\'s suicide. The war in the Pacific ended with Japan\'s surrender in September 1945, after the United States dropped atomic bombs on Hiroshima and Nagasaki.','uploads/img_682745dbe6148.jpg','approved','2025-05-16 14:04:11','2025-07-03 13:44:30',5),(5,'Climate Change: A Planetary Crisis Demanding Urgent Action','The Earth\'s climate is changing at an unprecedented rate, driven by human activities that are releasing greenhouse gases into the atmosphere. This phenomenon, known as climate change, is no longer a distant threat; it is a present reality with far-reaching consequences for our planet and all life on it. From rising sea levels and extreme weather events to disruptions in ecosystems and threats to human health, the evidence is irrefutable, and the need for urgent action has never been more critical.\r\n\r\n\r\n\r\nThe Science is Clear: The overwhelming scientific consensus, supported by decades of research from institutions worldwide, points to the burning of fossil fuels (coal, oil, and natural gas) as the primary driver of this change. These activities release carbon dioxide (CO \r\n2\r\n​\r\n ) and other greenhouse gases, such as methane (CH \r\n4\r\n​\r\n ) and nitrous oxide (N \r\n2\r\n​\r\n O), into the atmosphere. These gases trap heat, leading to a gradual warming of the planet. Deforestation, industrial processes, and intensive agriculture also contribute significantly to greenhouse gas emissions.\r\n\r\n\r\n\r\n\r\nObserved Impacts: The effects of climate change are already being felt across the globe:\r\n\r\nRising Global Temperatures: The average global temperature has increased significantly over the past century, and the last decade has been the warmest on record. This warming trend is causing heatwaves, longer and hotter summers, and disruptions to natural cycles.\r\n\r\nMelting Ice and Rising Sea Levels: Polar ice caps and glaciers are melting at an alarming rate, contributing to a rise in global sea levels. This threatens coastal communities with increased flooding, erosion, and displacement. Low-lying island nations are particularly vulnerable.\r\n\r\n\r\nExtreme Weather Events: Climate change is intensifying extreme weather events. We are witnessing more frequent and severe heatwaves, droughts, wildfires, floods, and powerful storms. These events cause widespread devastation, loss of life, and significant economic damage. For instance, the increased intensity of cyclones in coastal regions like Bangladesh is a stark reminder of these impacts.\r\n\r\n\r\nOcean Acidification: The absorption of excess CO \r\n2\r\n​\r\n  by the oceans is causing them to become more acidic. This poses a significant threat to marine ecosystems, particularly coral reefs and shellfish, which are vital for ocean biodiversity and food security.\r\n\r\nDisruption of Ecosystems: Changes in temperature and precipitation patterns are disrupting ecosystems worldwide. Species are being forced to migrate, face extinction, or experience changes in their behavior and interactions. This loss of biodiversity weakens the resilience of ecosystems and can have cascading effects.\r\n\r\n\r\nThreats to Human Health: Climate change has direct and indirect impacts on human health. Heatwaves can lead to heatstroke and other heat-related illnesses. Changes in vector-borne disease distribution, increased respiratory problems due to air pollution linked to fossil fuels and wildfires, and food and water insecurity are all exacerbated by climate change.\r\n\r\nThe Path Forward: Mitigation and Adaptation: Addressing climate change requires a two-pronged approach:\r\n\r\nMitigation: This involves reducing greenhouse gas emissions. The most crucial step is the transition away from fossil fuels towards renewable energy sources like solar, wind, and hydro power. Improving energy efficiency, promoting sustainable transportation, adopting sustainable agricultural practices, and preventing deforestation are also essential mitigation strategies. International agreements, such as the Paris Agreement, aim to set targets and frameworks for global emissions reductions.\r\n\r\nAdaptation: Even with ambitious mitigation efforts, some level of climate change is already locked in. Adaptation involves taking steps to prepare for and adjust to the current and future impacts of climate change. This includes building seawalls, developing drought-resistant crops, improving water management systems, and strengthening public health infrastructure to deal with climate-related health risks. For a country like Bangladesh, adaptation strategies are critical due to its vulnerability to sea-level rise and extreme weather events.\r\nThe Urgency of Action: The window of opportunity to limit the most severe impacts of climate change is rapidly closing. Delaying action will only lead to more significant and irreversible consequences, increasing costs and suffering in the future. A global, coordinated effort involving governments, businesses, communities, and individuals is essential to transition to a sustainable and resilient future','uploads/img_68274d08a8a70.jpeg','approved','2025-05-16 14:34:48','2025-07-03 13:44:32',6),(6,'What is machine learning?','Since deep learning and machine learning tend to be used interchangeably, it’s worth noting the nuances between the two. Machine learning, deep learning, and neural networks are all sub-fields of artificial intelligence. However, neural networks is actually a sub-field of machine learning, and deep learning is a sub-field of neural networks.\r\n\r\nThe way in which deep learning and machine learning differ is in how each algorithm learns. \"Deep\" machine learning can use labeled datasets, also known as supervised learning, to inform its algorithm, but it doesn’t necessarily require a labeled dataset. The deep learning process can ingest unstructured data in its raw form (e.g., text or images), and it can automatically determine the set of features which distinguish different categories of data from one another. This eliminates some of the human intervention required and enables the use of large amounts of data. You can think of deep learning as \"scalable machine learning\" as Lex Fridman notes in this MIT lecture1.\r\n\r\nClassical, or \"non-deep,\" machine learning is more dependent on human intervention to learn. Human experts determine the set of features to understand the differences between data inputs, usually requiring more structured data to learn.\r\n\r\nNeural networks, or artificial neural networks (ANNs), are comprised of node layers, containing an input layer, one or more hidden layers, and an output layer. Each node, or artificial neuron, connects to another and has an associated weight and threshold. If the output of any individual node is above the specified threshold value, that node is activated, sending data to the next layer of the network. Otherwise, no data is passed along to the next layer of the network by that node. The “deep” in deep learning is just referring to the number of layers in a neural network. A neural network that consists of more than three layers, which would be inclusive of the input and the output can be considered a deep learning algorithm or a deep neural network. A neural network that only has three layers is just a basic neural network.','uploads/img_682777ec2a41f.jfif','approved','2025-05-16 17:37:48','2025-08-29 15:41:50',3),(8,'The Role and Importance of Universities in Modern Society','Universities are the cornerstone of higher education, research, and innovation in society. They provide students with the knowledge, skills, and critical thinking abilities necessary to succeed in professional and personal life. Unlike schools, universities offer specialized courses in a wide range of fields, including science, arts, technology, medicine, and business. This allows students to focus on areas of their interest and pursue careers that match their passion and aptitude.\r\n\r\nApart from academic learning, universities play a crucial role in personal development. They create an environment where students learn to communicate effectively, work in teams, and develop leadership qualities. Universities also encourage extracurricular activities such as sports, cultural events, and volunteer programs, which help students grow into well-rounded individuals.\r\n\r\nResearch and innovation are other significant aspects of universities. Professors and students engage in research projects that contribute to scientific advancements and address societal challenges. Many groundbreaking discoveries, inventions, and technological developments originate from university research labs.\r\n\r\nFurthermore, universities act as cultural and intellectual hubs. They bring together people from diverse backgrounds, fostering understanding, tolerance, and collaboration. International students and academic exchanges promote global perspectives, preparing students to work in a connected and interdependent world.\r\n\r\nIn conclusion, universities are not just institutions of learning; they are engines of progress, shaping the future of society. By providing education, promoting research, and nurturing personal growth, universities play an indispensable role in building a knowledgeable, skilled, and responsible generation.','uploads/img_68b1ca220e294.jpeg','pending','2025-08-29 15:41:22','2025-09-07 14:53:29',3),(9,'AI and China: Shaping the Future of Global Technology','In recent years, China has emerged as one of the leading players in the development and application of Artificial Intelligence (AI). With massive investments, supportive government policies, and a rapidly growing tech ecosystem, the country is positioning itself as a global hub for AI innovation, challenging the dominance of the United States and Europe.\r\n\r\nChina’s AI strategy focuses on integrating advanced technologies into key sectors such as healthcare, transportation, education, manufacturing, and national defense. The government’s New Generation Artificial Intelligence Development Plan, launched in 2017, set the ambitious goal of making China the world leader in AI by 2030. This roadmap has spurred intense research, talent cultivation, and collaborations between the state, private companies, and universities.\r\n\r\nOne of China’s major strengths lies in its vast population, which generates enormous amounts of data — the lifeblood of AI. Tech giants such as Baidu, Alibaba, Tencent, and Huawei are leveraging this advantage to build powerful AI applications, from facial recognition and smart city management to e-commerce personalization and autonomous driving.\r\n\r\nHowever, China’s AI push has also sparked international debate. Concerns over privacy, surveillance, and ethical use of AI remain high, especially with the widespread use of facial recognition in public spaces. Additionally, geopolitical tensions between China and Western nations have led to stricter regulations, export controls, and competition in the race for AI supremacy.\r\n\r\nDespite these challenges, China’s progress in AI is undeniable. With increasing global influence, the country is shaping not only the technology itself but also the policies and ethical frameworks that may guide AI use worldwide. As nations compete and cooperate in this new digital era, AI will remain a defining factor in determining economic strength, security, and innovation in the 21st century.','uploads/img_68bd9af237562.jpeg','approved','2025-09-07 14:47:14','2025-09-07 14:53:21',6),(10,'The Rise of Electric Vehicles in Asia','The electric vehicle (EV) revolution is no longer a distant dream—it is happening now, and Asia is at the center of this transformation. Once dominated by traditional fossil-fuel cars, the automotive industry is rapidly shifting toward cleaner, smarter, and more sustainable mobility. From China and Japan to India and Southeast Asia, governments and businesses are betting on EVs as the future of transportation.\r\n\r\nChina Leading the Charge\r\n\r\nChina is undoubtedly the global leader in electric mobility. With companies like BYD, NIO, and Xpeng gaining international recognition, China has become the largest EV market in the world. In 2024 alone, Chinese EV sales surpassed those of North America and Europe combined. The government’s strong incentives, subsidies, and investment in charging infrastructure have created an ecosystem where EVs are not just a luxury but a mainstream option for middle-class buyers.\r\n\r\nBattery technology is another area where China is setting the pace. Companies such as CATL (Contemporary Amperex Technology Co. Limited) are pioneering lithium-ion and sodium-ion batteries, making EVs more affordable and longer-lasting. As battery costs continue to decline, experts predict that EVs will soon reach price parity with gasoline vehicles.\r\n\r\nJapan and South Korea: Innovation Hubs\r\n\r\nWhile China focuses on scale, Japan and South Korea are making strides in innovation. Japan’s Toyota and Honda are investing heavily in hybrid and hydrogen fuel cell vehicles, while Nissan, once a pioneer with the Leaf, is renewing its push into the EV space. South Korean companies like Hyundai and Kia are producing some of the most stylish and efficient EVs in the global market, winning consumers with strong design and advanced technology.\r\n\r\nBoth countries are also investing in next-generation batteries, with an emphasis on safety, sustainability, and faster charging times. Their approach is not just about selling cars but about building a technological edge for the future.\r\n\r\nIndia and Southeast Asia: Emerging Players\r\n\r\nIndia, with its massive population and growing economy, is an emerging hotspot for EV adoption. The government has introduced the FAME (Faster Adoption and Manufacturing of Hybrid and Electric Vehicles) scheme to encourage both manufacturers and consumers to embrace clean mobility. Companies like Tata Motors and Ola Electric are leading the domestic EV market, while global brands are eyeing India as a key growth region.\r\n\r\nIn Southeast Asia, countries like Thailand, Vietnam, and Indonesia are taking bold steps to develop EV industries. Thailand aims to become the EV production hub of ASEAN, while Indonesia is leveraging its rich nickel reserves to attract battery manufacturers. These efforts highlight the region’s determination to not be left behind in the global EV race.\r\n\r\nChallenges Along the Way\r\n\r\nDespite rapid growth, the road ahead for EVs in Asia is not without obstacles. One of the biggest challenges is charging infrastructure. While China has built a vast network of charging stations, other countries are still catching up. Rural areas, in particular, face difficulties in adopting EVs due to limited access to reliable charging facilities.\r\n\r\nAnother issue is affordability. Although battery prices are dropping, EVs remain relatively expensive compared to traditional cars in many Asian markets. Governments must balance subsidies with long-term sustainability while ensuring that EVs are accessible to ordinary citizens.\r\n\r\nEnvironmental concerns also remain. While EVs reduce emissions on the road, battery production and disposal still pose environmental challenges. Recycling systems and greener energy grids will be essential to truly realize the promise of clean mobility.\r\n\r\nA Green Future for Asia\r\n\r\nThe rise of EVs in Asia represents more than just a technological shift—it symbolizes a move toward a greener and more sustainable future. By embracing electric mobility, Asian countries are reducing their dependence on imported oil, lowering air pollution in crowded cities, and taking a leadership role in the fight against climate change.\r\n\r\nAs innovation accelerates, costs fall, and infrastructure expands, EVs are set to dominate Asia’s roads in the coming decades. Whether it is a high-tech EV from China, a hydrogen-powered car from Japan, or a budget-friendly scooter from India, Asia is driving the world toward a cleaner tomorrow.','uploads/img_68bd9c44bbb8c.jpeg','approved','2025-09-07 14:52:52','2025-09-07 14:53:22',6),(11,'Cybersecurity: A Growing Global Threat','In today’s hyper-connected world, cybersecurity has become one of the most pressing issues facing individuals, businesses, and governments alike. As our reliance on digital systems deepens—from online banking and e-commerce to healthcare and national security—the risks of cyberattacks grow more severe and widespread. What was once a niche concern for IT departments has now become a global challenge that affects everyone.\r\n\r\nThe Scale of the Problem\r\n\r\nAccording to recent reports, cybercrime is projected to cost the world over $10 trillion annually by 2025, making it more profitable than the global trade of illegal drugs. From ransomware attacks that paralyze hospitals and schools, to data breaches that expose millions of users’ personal information, no sector is immune.\r\n\r\nHigh-profile cases such as the Colonial Pipeline ransomware attack in the United States, or data leaks affecting millions of users across Asia and Europe, have underscored how vulnerable critical infrastructure and personal data are in the digital age.\r\n\r\nCommon Types of Cyber Threats\r\n\r\nCybersecurity risks take many forms, including:\r\n\r\nPhishing Attacks – Fraudulent emails or messages that trick individuals into revealing sensitive information.\r\n\r\nRansomware – Malicious software that locks data or systems until a ransom is paid.\r\n\r\nData Breaches – Unauthorized access to sensitive databases containing personal or financial information.\r\n\r\nDDoS Attacks (Distributed Denial of Service) – Flooding systems with traffic to overwhelm and shut down services.\r\n\r\nState-Sponsored Cyberwarfare – Governments using cyber tools to disrupt, spy, or influence rivals.\r\n\r\nThese threats not only cause financial damage but also erode trust in digital platforms and services.\r\n\r\nWhy Cybersecurity Matters Globally\r\n\r\nThe digital revolution has blurred national borders. A hacker operating from one country can infiltrate systems across the world within seconds. This makes cybersecurity not just a local issue but a global one.\r\n\r\nFor Businesses – Cyberattacks can result in financial losses, legal penalties, and reputational harm. Small businesses are particularly vulnerable due to weaker security systems.\r\n\r\nFor Governments – Cyberwarfare poses national security risks, with attacks targeting defense systems, power grids, and public institutions.\r\n\r\nFor Individuals – Identity theft, online scams, and loss of privacy affect millions of internet users daily.\r\n\r\nThe Human Factor\r\n\r\nIronically, one of the biggest weaknesses in cybersecurity is not technology, but people. Human errors—such as weak passwords, careless clicking on malicious links, or using unsecured Wi-Fi—are often the entry points for cybercriminals. That’s why public awareness campaigns and digital literacy programs are becoming as important as firewalls and encryption software.\r\n\r\nThe Way Forward\r\n\r\nAddressing cybersecurity requires a multi-layered approach:\r\n\r\nStronger Infrastructure – Governments and organizations must invest in secure systems, regular updates, and advanced threat detection.\r\n\r\nInternational Cooperation – Cybercrime does not respect borders. Global cooperation is vital to track, regulate, and punish attackers.\r\n\r\nEducation and Awareness – Individuals must be trained to recognize threats and adopt safe online practices.\r\n\r\nAI and Automation – Artificial Intelligence can detect and respond to threats faster than human monitoring alone.\r\nPolicy and Regulation – Laws must evolve to ensure accountability for both attackers and organizations that fail to protect user data.','uploads/img_68bda46aeb6a5.jpeg','pending','2025-09-07 15:27:38','2025-09-12 15:04:27',5);
/*!40000 ALTER TABLE `articles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comment_settings`
--

DROP TABLE IF EXISTS `comment_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment_settings` (
  `article_id` int(11) NOT NULL,
  `comments_enabled` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`article_id`),
  CONSTRAINT `comment_settings_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`article_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comment_settings`
--

LOCK TABLES `comment_settings` WRITE;
/*!40000 ALTER TABLE `comment_settings` DISABLE KEYS */;
INSERT INTO `comment_settings` VALUES (1,1),(3,1),(4,1),(5,1),(6,1);
/*!40000 ALTER TABLE `comment_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `name` text NOT NULL,
  `status` enum('pending','approved') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`comment_id`),
  KEY `article_id` (`article_id`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`article_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (5,4,'very nice article','2025-05-29 15:40:02','Reader 1','approved'),(6,6,'good','2025-05-29 15:45:57','Reader 2','approved'),(7,8,'very nice','2025-08-29 15:42:54','Rifat','approved'),(8,8,'very nice','2025-08-29 15:43:38','Rifat','pending');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `popups`
--

DROP TABLE IF EXISTS `popups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `popups` (
  `popup_id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`popup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `popups`
--

LOCK TABLES `popups` WRITE;
/*!40000 ALTER TABLE `popups` DISABLE KEYS */;
/*!40000 ALTER TABLE `popups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscription_plans`
--

DROP TABLE IF EXISTS `subscription_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscription_plans` (
  `plan_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration_days` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscription_plans`
--

LOCK TABLES `subscription_plans` WRITE;
/*!40000 ALTER TABLE `subscription_plans` DISABLE KEYS */;
/*!40000 ALTER TABLE `subscription_plans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`tag_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (11,'AI'),(14,'Asia'),(10,'China'),(5,'Climate Change'),(18,'Cybersecurity'),(20,'DigitalSafety'),(8,'education'),(15,'ElectricVehicles'),(13,'Environment'),(3,'Germany'),(19,'GlobalIssues'),(6,'hobby'),(16,'Innovation'),(7,'my'),(2,'Politics'),(21,'Security'),(17,'Sustainability'),(1,'Tech'),(12,'Technology'),(9,'University'),(4,'Weather');
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_subscriptions`
--

DROP TABLE IF EXISTS `user_subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_subscriptions` (
  `subscription_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `start_date` datetime DEFAULT current_timestamp(),
  `end_date` datetime NOT NULL,
  `status` enum('active','expired') DEFAULT 'active',
  PRIMARY KEY (`subscription_id`),
  KEY `user_id` (`user_id`),
  KEY `plan_id` (`plan_id`),
  CONSTRAINT `user_subscriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `user_subscriptions_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `subscription_plans` (`plan_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_subscriptions`
--

LOCK TABLES `user_subscriptions` WRITE;
/*!40000 ALTER TABLE `user_subscriptions` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_subscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('journalist','editor','user') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `Is_active` int(11) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (2,'editor','editor@gmail.com','$2y$10$8d9RtAJ0IZI7i89AD00t1utuCahb4Nxx4SWh1Pa.kWzFv8hOnUA8C','editor','2025-05-14 06:09:40',0),(3,'journalist1','journalist1@gmail.com','$2y$10$JDjXiTm.G6fM/0To6zbZdOOWXdhMfzn5TY1OLhSUS6A.6guKGYCvm','journalist','2025-05-15 16:34:00',0),(5,'journalist2','Journalist2@gmail.com','$2y$10$c.y98tOBXgh8bFuDPSXCu.28YzySvedfcWjI4moO2DzgEzzh9Kdl6','journalist','2025-05-16 14:01:20',0),(6,'journalist3','journalist3@gmail.com','$2y$10$G37IfkXka1oZNigaV1UDUuIqig9BE6UzB69WAVAmd076/zVoETWTi','journalist','2025-05-16 14:32:07',0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-19 23:08:52
