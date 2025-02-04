


    
        
                CREATE TABLE IF NOT EXISTS MESSAGES
                    (
                    USERID TEXT NOT NULL,
                    SENDERID TEXT NULL,
                    MSGID TEXT NULL,
                    MSG TEXT NULL,
                    FUNNY TEXT NULL,
                    SAD TEXT NULL,
                    BORING TEXT NULL,
                    CRAZY TEXT NULL,
                    CTIME TIMESTAMP NOT NULL,
                    USERNAME VARCHAR(255),
                    FOREIGN KEY (USERNAME) REFERENCES INFO (USERNAME)
                    ) ;
        

         CREATE TABLE  IF NOT EXISTS INFO
                (
                    ID TEXT NULL,
                    USERNAME VARCHAR(255) PRIMARY KEY,
                    REMEMBER VARCHAR(100) NULL,
                    PASSWORD TEXT NOT NULL
                );
            
            
                CREATE TABLE IF NOT EXISTS SELECTTABLE(
                    ID TEXT NOT NULL,
                    SELECTID VARCHAR(100) NOT NULL
                
            );

      