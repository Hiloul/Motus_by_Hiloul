<?php
class Score {
    public function getScore() {
        return isset($_SESSION['score']) ? $_SESSION['score'] : 0;
    }

    public function incrementScore($increment) {
        if (!isset($_SESSION['score'])) {
            $_SESSION['score'] = 0;
        }

        $_SESSION['score'] += $increment;
    }

    public function resetScore() {
        $_SESSION['score'] = 0;
    }
}
?>
