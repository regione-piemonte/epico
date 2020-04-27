<?php
/*
SPDX-FileCopyrightText: Copyright 2015 | Regione Piemonte |
SPDX-License-Identifier: EUPL-1.2-or-later
*/
	
class Risorse extends CI_Model {
	function __construct()
	{
	  // Call the Model constructor
	  parent::__construct();
	
	}
	
	function get($comune, $tipologia)
	{
		$this->db->where('comune', $comune);
		$this->db->where('tipologia', $tipologia);
		$query = $this->db->get('importi');
        return $query->result();
	}
	
	function getTotale($comune, $tipologia)
	{
		$this->db->where('comune', $comune);
		$this->db->where('tipologia', $tipologia);
		$this->db->select_sum('importo');
		$query = $this->db->get('importi');
		$result = $query->result();
        return $result[0]->importo;
	}
	
	function getResidue($comune)
	{
		$query = $this->db->query('SELECT assegnati.annualita, SUM(importo) - tot_liquidati AS importo 
									FROM importi AS assegnati JOIN 
									(SELECT importi_liquidati.annualita, SUM(importo) AS tot_liquidati 
										FROM importi AS importi_liquidati 
										WHERE comune = "' . $comune . '" AND tipologia = "liquidati" 
										GROUP BY annualita
									) AS liquidati ON assegnati.annualita = liquidati.annualita
									WHERE comune = "' . $comune . '" AND tipologia = "assegnati" 
									GROUP BY annualita');
		return $query->result();
	}
	
	function insert()
	{
		$this->data_determina = ($this->data_determina) ? DateTime::createFromFormat('d/m/Y', $this->data_determina)->format('Y-m-d') : null;
		$this->num_determina = ($this->num_determina) ? $this->num_determina : null;
		$this->al = ($this->al) ? $this->al : null;
		$this->data_al = ($this->data_al) ? DateTime::createFromFormat('d/m/Y', $this->data_al)->format('Y-m-d') : null;
		$this->capitolo = ($this->capitolo) ? $this->capitolo : null;
		$this->anno_capitolo = ($this->anno_capitolo) ? $this->anno_capitolo : null;
		$this->num_impegno = ($this->num_impegno) ? $this->num_impegno : null;
		$this->db->insert('importi', $this);
        return $this->db->insert_id();
	}
	
	function delete($id)
	{     
		$this->db->delete('importi', array('id' => $id));
	}
	
}