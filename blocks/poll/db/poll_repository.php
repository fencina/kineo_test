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

    public function get_poll_by_block($blockid)
    {
        return $this->db->get_record(self::TABLE_POLLS, compact('blockid'));
    }

    public function get_poll_by_id($id)
    {
        $poll = $this->db->get_record(self::TABLE_POLLS, compact('id'));

        $poll->options = $this->get_options_for_poll($poll->id);
        return $poll;
    }

    public function create_poll($data)
    {
        return $this->db->insert_record(self::TABLE_POLLS, $data);
    }

    public function update_poll($poll)
    {
        return $this->db->update_record(self::TABLE_POLLS, $poll);
    }

    public function get_options_for_poll($pollid)
    {
        return $this->db->get_records(self::TABLE_POLL_OPTIONS, compact('pollid'));
    }

    public function create_option($data)
    {
        return $this->db->insert_record(self::TABLE_POLL_OPTIONS, $data);
    }

    public function update_option($option)
    {
        return $this->db->update_record(self::TABLE_POLL_OPTIONS, $option);
    }

    public function delete_option($id)
    {
        return $this->db->delete_records(self::TABLE_POLL_OPTIONS, compact('id'));
    }

    public function create_answer($data)
    {
        return $this->db->insert_record(self::TABLE_POLL_ANSWERS, $data);
    }

    public function get_answers_for_poll_and_user($pollid, $userid)
    {
        return $this->db->get_record(self::TABLE_POLL_ANSWERS, compact('pollid', 'userid'));
    }

    public function get_answers_for_poll($pollid)
    {
        return $this->db->get_records(self::TABLE_POLL_ANSWERS, compact('pollid'));
    }

    public function get_poll_results($pollid)
    {
        $sql = "select po.id, po.tag, IF(pa.id is not null, count(*), 0) as 'answers_count'
                from poll_options as po
                left join poll_answers as pa on pa.polloptionid = po.id and po.pollid = pa.pollid
                where po.pollid = ?
                group by po.id
                ;";

        return $this->db->get_records_sql($sql, [$pollid]);
    }
}
