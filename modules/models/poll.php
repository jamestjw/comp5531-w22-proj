<?php

require_once(dirname(__FILE__)."/record.php");

class Poll extends Record
{
    protected static $table_name = "polls";
    protected static $has_many = array(
        "poll_options" => array(
            "class_name" => "PollOption",
            "foreign_key" => "poll_id",
        )
    );
    // TODO: Polls could have a polymorphic parent
    protected static $belongs_to = array(
        "discussion_message" => array(
            "class_name" => "DiscussionMessage",
            "foreign_key" => "parent_id",
        )
    );

    public int $id;
    public int $parent_id;
    public int $user_id;
    public string $title;
    // TODO: We are using this to avoid the need
    // for a date and time picker, improve this
    // if possible!
    public int $duration;
    public string $created_at;
    public string $updated_at;

    // Returns true if user has voted in this poll
    public function user_has_voted($user_id): bool
    {
        $option_ids = array_map(fn ($opt) => $opt->id, $this->poll_options);

        return null !== PollOptionUser::find_by(array("user_id"=>$user_id, "option_id"=>$option_ids));
    }
}

class PollResult
{
    public int $num_votes;
    /*
    Keys are poll option IDs
    Values are [vote_count, vote_percentage] tuples
    array(
        1 => [2, 0.2],
        2 => [3, 0.3],
        3 => [5, 0.5]
    )
    */
    public $votes;

    public static function from_poll($poll)
    {
        $res = new PollResult();
        // TODO: Improve this by implementing joins and group by in the Record class
        $sql = "SELECT po.id, count(user_id) vote_count FROM poll_options po LEFT JOIN poll_option_users pou on po.id = pou.option_id WHERE poll_id = {$poll->id} GROUP BY po.id;";

        $statement = getConnection()->prepare($sql);
        $statement->execute();
        $total_count = 0;

        foreach ($statement->fetchAll() as $option_res) {
            $total_count += $option_res['vote_count'];
            $res->votes[$option_res['id']] = $option_res['vote_count'];
        }
        $res->num_votes = $total_count;

        foreach ($res->votes as $id=>$vote_count) {
            $res->votes[$id] = [$vote_count, $vote_count/$total_count];
        }

        return $res;
    }
}
