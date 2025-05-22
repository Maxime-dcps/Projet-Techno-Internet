INSERT INTO types_oeuvre (nom_type, description_type) VALUES
('Peinture', 'Là où la couleur prend âme et la toile devient miroir des songes.'),
('Sculpture', 'Quand la matière s''éveille, offrant au toucher l''écho d''une pensée figée.'),
('Photographie', 'L''instant suspendu, où la lumière grave l''empreinte fugace du réel ou du rêvé.'),
('Art Numérique', 'Le souffle de l''algorithme, où le pixel devient poésie et l''écran une porte vers l''ailleurs.'),
('Dessin', 'Le murmure intime du trait, première trace de la pensée effleurant le silence du papier.');

INSERT INTO utilisateurs (username, email, hash, role, est_actif) VALUES
('galerie_horizon', 'contact@galeriehorizon.com', '$2y$10$xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 'utilisateur', TRUE),
('sophie_leroy_art', 's.leroy@emailartist.com', '$2y$10$yyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy', 'utilisateur', TRUE),
('marc_dubois_collection', 'marc.dubois.collection@email.net', '$2y$10$zzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz', 'utilisateur', TRUE),
('leo_marchand_studio', 'leo.marchand@studiocreatif.com', '$2y$10$aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'utilisateur', TRUE),
('clara_valentin_photo', 'cvalentin.photo@capture.org', '$2y$10$bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb', 'utilisateur', TRUE),
('elodie_petit_design', 'elodie.petit@designgraphique.dev', '$2y$10$ccccccccccccccccccccccccccccccccccccccccccccccccccc', 'utilisateur', FALSE);

INSERT INTO oeuvres (titre, description, artiste, annee_creation, dimensions, prix, image_url, statut_oeuvre, id_type_oeuvre, id_utilisateur) VALUES

('Nu bleu I', 'Gouache découpée emblématique de la dernière période de l''artiste.', 'Henri Matisse', 1952, '106cm x 78cm', 25000.00, 'images/matisse_nubleu1.jpg', 'disponible', 1, 3),
('Les Nymphéas: Le Matin Clair aux Saules', 'Partie du célèbre cycle des Nymphéas, capturant la lumière et l''eau.', 'Claude Monet', 1915, '200cm x 1275cm (ensemble)', 500000.00, 'images/monet_nympheas_matinclair.jpg', 'disponible', 1, 1),
('Figure déstructurée', 'Composition abstraite cubiste explorant la forme et l''espace.', 'Maria Blanchard', 1917, '81cm x 60cm', 3500.00, 'images/blanchard_figure_destructuree.jpg', 'disponible', 1, 3),
('Black Iris III', 'Fleur iconique, agrandie et détaillée, typique du modernisme américain.', 'Georgia O''Keeffe', 1926, '91.4cm x 75.9cm', 40000.00, 'images/okeeffe_blackiris3.jpg', 'réservée', 1, 1),
('Le Rêve du Pêcheur', 'Scène onirique et symboliste aux couleurs vibrantes.', 'Leonor Fini', 1947, '65cm x 81cm', 1200.00, 'images/fini_reve_pecheur.jpg', 'disponible', 1, 2),
('Composition VIII', 'Œuvre clé de l''abstraction lyrique, explorant la relation entre couleur et musique.', 'Vassily Kandinsky', 1923, '140cm x 201cm', 150000.00, 'images/kandinsky_composition8.jpg', 'disponible', 1, 1),

('L''Homme qui marche I', 'Bronze iconique représentant la fragilité et la persévérance humaine.', 'Alberto Giacometti', 1960, 'H: 183cm', 650000.00, 'images/giacometti_hommequimarche.jpg', 'disponible', 2, 1),
('Maman (Araignée)', 'Sculpture monumentale en bronze et acier inoxydable. Une version plus petite ou une étude pourrait être vendue.', 'Louise Bourgeois', 1999, 'H: 927cm (grande version)', 280000.00, 'images/bourgeois_maman.jpg', 'vendue', 2, 3),
('Le Baiser', 'Sculpture en pierre calcaire, symbole de l''amour et de l''union.', 'Constantin Brâncuși', 1907, 'H: 27.9cm', 180000.00, 'images/brancusi_baiser.jpg', 'disponible', 2, 1),
('Balloon Dog (Orange)', 'Acier inoxydable avec revêtement de couleur transparent. Fait partie d''une série célèbre.', 'Jeff Koons', 1994, 'H: 307.3cm', 584000.00, 'images/koons_balloondog_orange.jpg', 'disponible', 2, 4),
('Venus de Milo aux tiroirs', 'Plâtre surréaliste avec des tiroirs, interrogeant le corps et l''inconscient.', 'Salvador Dalí', 1936, 'H: 98cm', 2500.00, 'images/dali_venus_tiroirs.jpg', 'disponible', 2, 3),
('Mobile sur deux plans', 'Mobile en tôle d''acier peinte, jouant avec l''équilibre et le mouvement.', 'Alexander Calder', 1955, 'Variable', 5000.00, 'images/calder_mobile_deuxplans.jpg', 'réservée', 2, 2),

('Migrant Mother', 'Portrait iconique de Florence Owens Thompson, symbole de la Grande Dépression.', 'Dorothea Lange', 1936, 'Tirage 28x35cm', 3000.00, 'images/lange_migrantmother.jpg', 'disponible', 3, 5),
('Le Baiser de l''Hôtel de Ville', 'Photographie romantique d''un couple s''embrassant dans les rues de Paris.', 'Robert Doisneau', 1950, 'Tirage 30x40cm', 1550.00, 'images/doisneau_baiser_hoteldeville.jpg', 'vendue', 3, 1),
('Identical Twins, Roselle, New Jersey', 'Portrait troublant de deux sœurs jumelles.', 'Diane Arbus', 1967, 'Tirage 38x37cm', 4500.00, 'images/arbus_identicaltwins.jpg', 'disponible', 3, 5),
('Rhein II', 'Photographie de paysage minimaliste et épurée du Rhin.', 'Andreas Gursky', 1999, 'Tirage 185x363cm', 43000.00, 'images/gursky_rhein2.jpg', 'disponible', 3, 4),
('Derrière la Gare Saint-Lazare', 'Photographie capturant "l''instant décisif".', 'Henri Cartier-Bresson', 1932, 'Tirage 24x36cm', 1800.00, 'images/cartierbresson_derrieregare.jpg', 'disponible', 3, 1),
('Untitled Film Still #21', 'Autoportrait de l''artiste dans des mises en scène évoquant des films noirs.', 'Cindy Sherman', 1978, 'Tirage 20x25cm', 9000.00, 'images/sherman_filmstill21.jpg', 'réservée', 3, 2),

('Everydays: The First 5000 Days', 'Collage de 5000 images numériques créées quotidiennement, vendu comme NFT unique.', 'Beeple (Mike Winkelmann)', 2021, 'NFT (21069x21069px)', 693000.00, 'images/beeple_everydays.jpg', 'vendue', 4, 4),
('Portrait of Edmond de Belamy', 'Portrait généré par un algorithme d''IA (GAN), vendu aux enchères comme tirage unique encadré.', 'Obvious Art', 2018, 'Impression sur toile 70x70cm', 4325.00, 'images/obvious_edmondbelamy.jpg', 'disponible', 4, 1),
('Chromie Squiggle #78', 'Œuvre d''art génératif unique de la série "Chromie Squiggle", créée sur la plateforme Art Blocks. NFT.', 'Snowfro (Erick Calderon)', 2020, 'NFT (Généré dynamiquement)', 1500.00, 'images/snowfro_chromiesquiggle78.png', 'disponible', 4, 2),
('Autoglyph #17', 'Art génératif unique créé et stocké entièrement sur la blockchain Ethereum. Un des 512 Autoglyphs.', 'Larva Labs', 2019, 'NFT (ASCII Art)', 3500.00, 'images/larvalabs_autoglyph17.png', 'disponible', 4, 4),
('Generative Masks - Series 3, #12', 'Masque numérique unique créé par un algorithme génératif, présenté comme NFT et/ou tirage 3D.', 'Anna Ridler', 2019, 'NFT + Fichier 3D', 8000.00, 'images/ridler_generativemask.jpg', 'disponible', 4, 1),
('Hommage à Vera Molnar - Carrés (Variation)', 'Tirage numérique issu d''un programme informatique explorant les variations aléatoires de carrés. Édition limitée.', 'Artiste Contemporain (inspiré par Molnar)', 2022, 'Tirage pigmentaire 60x60cm', 3500.00, 'images/hommage_molnar_carres.jpg', 'réservée', 4, 3),

('Étude pour Guernica (Cheval)', 'Dessin préparatoire pour la célèbre peinture Guernica.', 'Pablo Picasso', 1937, 'Crayon sur papier 24x45cm', 2000.00, 'images/picasso_etude_guernica_cheval.jpg', 'disponible', 5, 3),
('Autoportrait aux sept doigts', 'Dessin onirique et symbolique de l''artiste.', 'Marc Chagall', 1912, 'Encre et aquarelle 28x21cm', 1500.00, 'images/chagall_autoportrait7doigts.jpg', 'disponible', 5, 1),
('No Title (Not a single spot of...', 'Dessin abstrait au crayon de couleur, typique de son style minimaliste.', 'Agnes Martin', 1967, 'Crayon et aquarelle sur papier 30x30cm', 3000.00, 'images/martin_nosinglespot.jpg', 'vendue', 5, 2),
('Le Grand Verre (études)', 'Ensemble de notes et de dessins préparatoires pour son œuvre majeure.', 'Marcel Duchamp', 1915, 'Crayon sur papier, divers formats', 50000.00, 'images/duchamp_etudes_grandverre.jpg', 'disponible', 5, 3),
('Saturn Devouring His Son (d''après Goya)', 'Dessin expressionniste sombre, réinterprétation d''une œuvre classique.', 'Paula Rego', 2005, 'Pastel sur papier 90x70cm', 80000.00, 'images/rego_saturn.jpg', 'disponible', 5, 4),
('One (Number 31, 1950) - étude', 'Dessin préparatoire ou lié à ses grandes peintures "drip painting".', 'Jackson Pollock', 1950, 'Encre et gouache sur papier 57x78cm', 1000.00, 'images/pollock_etude_one.jpg', 'réservée', 5, 1),
('Nicolas Cage Loves His Cat', 'Capture d''un moment d''affection simple et sincère, illustrant le lien fort entre l''homme et son animal de compagnie.', 'Lomlom Giovani', 2022, '40cm x 55cm', 450.00, 'images/c1a8af90-0461-4c28-a2ef-1248ecce099f.webp',  'disponible', 1, 2);