INSERT INTO `r_users` (`Users_USERNAME`, `Users_REFERENCED`, `Users_ROLES`,`Users_PASSWORD`)
VALUES (Username,referencedUser,userRole,AES_Encrypt(UPassword,PASSWORD('OSASMIS')))