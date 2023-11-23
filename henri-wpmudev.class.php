<?php

class HenriWPMUDev
{

    protected static $wpdb;
    protected static $table = 'book';
    public static $shortcode = 'henri-wpmudev';

    function __construct()
    {
        global $wpdb;
        self::$wpdb = $wpdb;
        self::$table = self::$wpdb->prefix . self::$table;
    }

    public static function createTable()
    {
        $table_name = self::$table;
        self::$wpdb->query("
            CREATE TABLE `{$table_name}` (
                `id` int(11) NOT NULL,
                `title` varchar(255) NOT NULL,
                `release` year(4) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    }

    public static function dropTable()
    {
        $table_name = self::$table;
        self::$wpdb->query("DROP TABLE {$table_name}");
    }

    public static function index()
    {
        return self::showTable();
        return self::showForm();
    }

    protected static function showForm()
    {
        return "
            <form method='POST'>
                <input type='hidden' name='book id'>
                <label for='title'>Title</label>
                <input type='text' name='title'>
                <br>
                <label for='release'>Release</label>
                <select name='release'>
                    <option value='2022'>2022</option>
                    <option value='2023'>2023</option>
                </select>
                <br>
                <input type='submit'>
            </form>
        ";
    }

    protected static function showTable()
    {
        return "
            <table border='1'>
                <thead>
                    <tr>
                        <th colspan='3'>
                            <input type='text' name='search book' placeholder='search book'>
                        </th>
                    </tr>
                    <tr>
                        <th>Title</th>
                        <th>Release</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        ";
    }

    protected static function insert()
    {
    }
}
