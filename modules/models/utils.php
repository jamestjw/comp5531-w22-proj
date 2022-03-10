<?php

function sql_log(string $raw_query, ?array $data = null)
{
    require dirname(__FILE__)."/../../config.php";

    // TODO: Is there a better log destination?
    $sql_log_path = dirname(__FILE__)."/../../$dbname.log";

    // Add timestamp
    $to_print = '['.date("F j, Y, g:i a").'] '.$raw_query;

    // Append args to the end of the message
    if (isset($data) && !empty($data)) {
        $args_texts = array();
        foreach ($data as $key=>$value) {
            array_push($args_texts, "['$key', '$value']");
        }
        $to_print .= " [".implode(', ', $args_texts)."]";
    }
    $to_print .= "\n";

    error_log($to_print, 3, $sql_log_path);
}

function execute_sql_query(string $raw_query, ?array $data = null, $conn = null): array
{
    sql_log($raw_query, $data);

    $conn = $conn ?? getConnection();
    $statement = $conn->prepare($raw_query);
    $statement->execute($data);
    return $statement->fetchAll();
}
