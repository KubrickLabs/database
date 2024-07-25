# Database

Database is a simple and efficient database abstraction layer built on top of PDO. It provides easy-to-use methods for common database operations like CRUD operations, transaction management, and connection pooling.

## Installation

You can install the package via Composer. Run the following command:

```bash
composer require kubricklabs/database
```

## Usage

### Basic Usage

Here's an example of how to use the Database package:

```php
require 'vendor/autoload.php';

use Database\Database;

// Database connection details
$dsn = 'your_dsn';
$username = 'your_username';
$password = 'your_password';
$options = [];

// Get a Database instance
$db = Database::getInstance($dsn, $username, $password, $options);

// Example CRUD operations
$result = $db->select('SELECT * FROM your_table');
$db->insert('your_table', ['column1' => 'value1', 'column2' => 'value2']);
$db->update('your_table', ['column1' => 'new_value'], ['column2' => 'value2']);
$db->delete('your_table', ['column1' => 'new_value']);

// Transaction management
$db->beginTransaction();
// Perform multiple CRUD operations
$db->commit();
```

### Connection Pooling

The library uses a connection pool to manage database connections efficiently. You don't need to manage the pool manually; the library takes care of it.

### Transaction Management

The `beginTransaction`, `commit`, and `rollBack` methods provide an easy way to manage database transactions.

```php
$db->beginTransaction();

try {
    // Perform multiple operations
    $db->insert('your_table', ['column1' => 'value1']);
    $db->update('your_table', ['column1' => 'new_value'], ['column2' => 'value2']);
    $db->commit();
} catch (Exception $e) {
    $db->rollBack();
    echo "Failed: " . $e->getMessage();
}
```

## Classes and Methods

### Database

The main class that extends PDO and provides methods for CRUD operations and transaction management.

#### Methods

- `select($query, $params = [], $fetchMode = PDO::FETCH_ASSOC)`
- `insert($table, $data)`
- `update($table, $data, $where)`
- `delete($table, $where)`
- `beginTransaction()`
- `commit()`
- `rollBack()`
- `getInstance($dsn, $username = null, $password = null, $options = null)`

### CRUDOperations

A helper class for performing CRUD operations.

#### Methods

- `select($query, $params = [], $fetchMode = PDO::FETCH_ASSOC)`
- `insert($table, $data)`
- `update($table, $data, $where)`
- `delete($table, $where)`

### TransactionManager

A helper class for managing transactions.

#### Methods

- `beginTransaction()`
- `commit()`
- `rollBack()`

### ConnectionPool

A helper class for managing a pool of database connections.

#### Methods

- `getConnection($dsn, $username, $password, $options)`
- `releaseConnection($connection)`

### ErrorHandler

A helper class for handling errors.

#### Methods

- `handleError(PDOException $e)`

## Contributing

Please feel free to submit issues and pull requests.

## License

MIT License. See [LICENSE](LICENSE) for more information.
```
