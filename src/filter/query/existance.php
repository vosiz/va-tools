<?php

namespace Vosiz\VaTools\Filter;

use Vosiz\Utils\Collections\Collection;

class Existance extends DataFilterQueryHandler {

    /**
     * Check existence in data defined by params
     * @param Collection $data collection where to look
     * @param array $params parameters
     * @return Collection (altered input)
     */
    public function Contains(Collection $data, array $params = array()) {
        
        $found = [];
        foreach($data->ToArray() as $key => $entry) {

            foreach($params as $p) {
                
                if($entry->HasValue($p)) {

                    $found[$key] = $entry;
                    break;
                }
            }
        }

        $data->RemoveExcept($found, true);
        
        return $data;
    }
}