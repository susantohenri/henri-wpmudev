<?php

class HenriWPMUDev
{

    protected static $wpdb;
    protected static $table = 'product';
    public static $shortcode = 'henri-wpmudev';

    function __construct()
    {
        global $wpdb;
        self::$wpdb = $wpdb;
        self::$table = self::$wpdb->prefix . self::$table;
        session_start();
    }

    public static function createTable()
    {
        $table_name = self::$table;
        self::$wpdb->query("
            CREATE TABLE `{$table_name}` (
                `id` int(11) NOT NULL,
                `item` varchar(255) NOT NULL,
                `stock` varchar(255) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
        self::$wpdb->query("ALTER TABLE `{$table_name}` ADD PRIMARY KEY (`id`)");
        self::$wpdb->query("ALTER TABLE `{$table_name}` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT");
    }

    public static function dropTable()
    {
        $table_name = self::$table;
        self::$wpdb->query("DROP TABLE {$table_name}");
    }

    public static function index()
    {
        if (isset($_POST['create_product'])) {
            return self::showForm();
        } else if (isset($_POST['edit_product'])) {
            return self::retrieve();
        } else if (isset($_POST['delete_product'])) {
            self::delete();
        } else if (isset($_POST['product_id'])) {
            if (empty($_POST['product_id'])) self::insert();
            else self::update();
        }
        return self::showTable();
    }

    protected static function showForm($record = null)
    {
        $record = !is_null($record) ? $record : (object)[
            'id' => '',
            'item' => '',
            'stock' => ''
        ];
        $csrf_token = md5(rand(0, 10000000)) . time();
        $_SESSION['csrf_token'] = $csrf_token;
        return "
            <form method='POST'>
                <input type='hidden' name='csrf_token' value='{$csrf_token}'>
                <input type='hidden' name='product_id' value='{$record->id}'>
                <label for='item'>item</label>
                <input type='text' name='item' value='{$record->item}'>
                <br>
                <label for='stock'>stock</label>
                <input type='text' name='stock' value='{$record->stock}'>
                <br>
                <input type='submit'>
                <a href=''>cancel</a>
            </form>
        ";
    }

    protected static function showTable()
    {
        return "
            <table border='1' width='100%' name='table_product'>
                <thead>
                    <tr>
                        <th colspan='3'>
                            <form method='POST'>
                                <input type='text' name='search_product' placeholder='search product'>
                                <button name='search_button'>search</button>
                                <button name='reset_button'>reset</button>
                                <input type='submit' name='create_product' value='create'>
                            </form>
                        </th>
                    </tr>
                    <tr>
                        <th>item</th>
                        <th>stock</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        ";
    }

    protected static function insert()
    {
        if ($_SESSION['csrf_token'] === $_POST['csrf_token']) {
            self::$wpdb->insert(self::$table, [
                'item' => sanitize_text_field($_POST['item']),
                'stock' => sanitize_text_field($_POST['stock'])
            ], ['%s', '%s']);
            $_SESSION['csrf_token'] = null;
        }
    }

    public static function list()
    {
        $table_name = self::$table;
        $query = "SELECT * FROM {$table_name}";
        if (!empty($_POST['keyword'])) $query .= " WHERE item LIKE '%{$_POST['keyword']}%'";
        return self::$wpdb->get_results($query);
    }

    protected static function delete()
    {
        self::$wpdb->delete(self::$table, ['id' => $_POST['product_id']]);
    }

    protected static function retrieve()
    {
        $table_name = self::$table;
        $products = self::$wpdb->get_results(self::$wpdb->prepare("SELECT * FROM {$table_name} WHERE id = %d", sanitize_text_field($_POST['product_id'])));
        if (!empty($products)) return self::showForm($products[0]);
    }

    protected static function update()
    {
        self::$wpdb->update(self::$table, [
            'item' => sanitize_text_field($_POST['item']),
            'stock' => sanitize_text_field($_POST['stock'])
        ], [
            'id' => sanitize_text_field($_POST['product_id'])
        ], ['%s', '%d'], ['%d']);
    }
}
