# Concaténation des champs adresses

UPDATE users SET `address` = CONCAT(`address_number`," ",`address`)

UPDATE users SET `address` = CONCAT(`address`," ",`address_details`)

# Ajout des roles par défaut

UPDATE users SET `roles` = "[\"ROLE_CHILD\"]" WHERE `birthday` > '2005-05-18'

UPDATE users SET `roles` = "[\"ROLE_PATIENT\"]" WHERE `roles` = ""

# Recherche de patients sans email, sans home_phone et sans cell_phone

SELECT \* FROM `users` WHERE `email` = "" AND `home_phone` = "" AND `cell_phone` = "" AND `roles` = "[\"ROLE_PATIENT\"]"

# Suppression des patients sans email, sans home_phone et sans cell_phone

DELETE FROM `users` WHERE `email` = "" AND `home_phone` = "" AND `cell_phone` = "" AND `roles` = "[\"ROLE_PATIENT\"]"
