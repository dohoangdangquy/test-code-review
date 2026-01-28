CREATE TABLE project ( -- Better use plural form (projects)
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    -- Add updated_at column for tracking modified
) Engine=InnoDB;

CREATE TABLE task ( -- Better use plural form (tasks)
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL, -- Need to create a foreign key between table project and task, using project_id with ON DELETE CASCADE, ON UPDATE CASCADE, update index for project_id (if not created default by database)
    title VARCHAR(255) NOT NULL,
    status VARCHAR(16) NOT NULL, -- Need to add a default value/make nullable
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    -- Add updated_at column for tracking modified
) Engine=InnoDB;
