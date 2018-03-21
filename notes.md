
	# Notes
	
	Fonctions de base:
	
	INSERT	INTO table IN database (JSON)				DONE
	UPDATE	table IN db (JSON)							DONE
	DELETE	table IN db (JSON)							DONE
	SELECT 	table IN db ([json])						DONE
	CREATE	DATABASE sname								DONE
			TABLE stbname IN sdbname (list of element,)	DONE
	/////
	
	INSERT INTO users IN amour ({"sName":"tigrou", "sPassword":"lol", "sUserName":"Tigroubinou"})
	
	Comment stocker ?
	
	Database
		Table
			meta
				data_name/file with meta
			key
				data_name/data
	
	Exemple:

		SELECT * WHERE username="bla" FROM users
		
	Retourne le contenue de
		data1/users/3e323e-212w12-d23s/username => bla

	Donc, comment faire...
	
	Ensuite:
		INSERT INTO user ({vale:data})
		
		
	
		
	
