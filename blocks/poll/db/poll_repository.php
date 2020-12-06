<?php

/**
 * Class poll_repository.
 * This class is an abstraction layer for DB access.
 */
class poll_repository {
    const TABLE_POLLS = 'polls';
    const TABLE_POLL_OPTIONS = 'poll_options';
    const TABLE_POLL_ANSWERS = 'poll_answers';

    protected $db;

    public function __construct()
    {
        global $DB;
        $this->db =& $DB;
    }

    public function get_poll_by_block($blockId)
    {
        return $this->db->get_record(self::TABLE_POLLS, array('blockid' => $blockId));
    }

    public function get_poll_by_id($pollId)
    {
        return $this->db->get_record(self::TABLE_POLLS, array('id' => $pollId));
    }

    public function create_poll($data)
    {
        return $this->db->insert_record(self::TABLE_POLLS, $data);
    }

    public function update_poll($poll)
    {
        return $this->db->update_record(self::TABLE_POLLS, $poll);
    }

    public function create_option($data)
    {
        return $this->db->insert_record(self::TABLE_POLL_OPTIONS, $data);
    }
}
