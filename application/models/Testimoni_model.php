<?php
class Testimoni_model extends CI_Model
{
    public $id;
    public $nama;
    public $email;
    public $wisata_id;
    public $profesi_id;
    public $rating;
    public $komentar;
    public $foto;

    public function getAll(){
        $query = $this->db->get('testimoni');
        return $query;
    }
    public function findById($id){
        $query = $this->db->get_where('testimoni',['id'=>$id]);
        return $query->row();
    }

    public function findByUserID($id){
        $query = $this->db->get_where('testimoni',['user_id'=>$id]);
        return $query;
    }

    public function findByProfesiID($id){
        $query = $this->db->get_where('testimoni',['profesi_id'=>$id]);
        return $query;
    }

    public function findByWisataId($id){
        $sql = "SELECT t.*, i.nama AS profesi FROM testimoni t JOIN profesi i ON t.profesi_id = i.id WHERE wisata_id = ? ORDER BY rating DESC";
        $query = $this->db->query($sql, array($id));
        return $query;
    }
    public function findByWisataIdOrderByCreated($id){
        $sql = "SELECT t.*, i.nama AS profesi FROM testimoni t JOIN profesi i ON t.profesi_id = i.id WHERE wisata_id = ? ORDER BY t.created_at DESC";
        $query = $this->db->query($sql, array($id));
        return $query;
    }
    public function findAllLimit($limit){
        $sql = "SELECT t.*, w.nama as wisata FROM testimoni t JOIN wisata w ON t.wisata_id = w.id ORDER BY t.created_at DESC LIMIT ?";
        $query = $this->db->query($sql, array($limit));
        return $query;
    }

    public function countStar($id){
        $sql = "SELECT COALESCE(ROUND(AVG(rating),0),0) AS bintang  FROM testimoni WHERE wisata_id = ?";
        $query = $this->db->query($sql, array($id));
        return $query->row()->bintang;
    }

    function delete($id)
    {
        $sql = "DELETE FROM testimoni WHERE id = ?";
        $query = $this->db->query($sql, array($id));
        return $query;
    }

    function updateByUser($data)
    {
        $sql = "UPDATE testimoni SET nama = ?, email = ?, profesi_id = ?, foto = ? WHERE user_id = ?";
        $query = $this->db->query($sql, array($data->nama, $data->email, $data->profesi_id, $data->foto, $data->id));
        return $query;
    }

    function save($data)
    {
        $sql = "INSERT INTO testimoni(nama, email, wisata_id, profesi_id, rating, komentar, user_id, foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $query = $this->db->query($sql, $data);
        return $query;
    }

    function update($data)
    {
        $sql = "UPDATE testimoni SET nama = ?, email = ?, wisata_id = ?, profesi_id = ?, rating = ?, komentar = ?, user_id = ?, created_at = ? WHERE id = ?";
        $query = $this->db->query($sql, array($data->nama, $data->email, $data->wisata_id, $data->profesi_id, $data->rating, $data->komentar, $data->user_id, $data->created_at, $data->id));
        return $query;
    }

}
