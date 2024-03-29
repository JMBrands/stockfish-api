<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stockfish API</title>
    <link href="styles.css" rel="stylesheet" />

    <?php
        function parseFEN($FEN) {
            $out = array();
            $index = 0;
            for ($y = 7; $y >= 0; $y--) {
                $empty = 0;
                $row = array();
                for ($x = 0; $x < 8; $x++) {
                    parsechar:
                    $current = $FEN[$index];
                    $filled = false;

                    switch ($current) {
                        case 'k': case 'q': case 'r': case 'b': case 'n': case 'p':
                        case 'K': case 'Q': case 'R': case 'B': case 'N': case 'P':
                            $row[] = $current;
                            $filled = true;
                            $index++;
                            break;
                        
                        default:
                            break;
                    }
                    if (!$filled) {
                        switch ($current) {
                            case '1': case '2' : case '3': case '4':
                            case '5' : case '6': case '7': case '8':
                                $i = 0;
                                for ($i = 0; $i < intval($current); $i++) {
                                    $row[] = ' ';
                                }
                                $x += $i-1;
                                $index++;
                                break;
                            case '/':
                                $index++;
                                goto parsechar;
                            default:
                                break;
                        }
                    }
                }
                $out[] = $row;
            }
            return $out;
        }
    ?>
</head>
<body>
    <div class="FEN">
    <?php
        $FEN = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo '<p>FEN: '. htmlspecialchars($_POST["FEN"]).'</p>';
            $FEN = parseFEN($_POST["FEN"]);
        } else {
            echo '<p>FEN: rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1</p>';
            $FEN = parseFEN('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1');
        }
    ?>
    </div>
    <div id="board">
        <?php
            parseFEN("hi");
            for ($x = 0; $x < 8; $x++) {
                for ($y = 0; $y < 8; $y+=2) {
                    if ($x%2) echo "<div class=\"black\" id=\"".['a','b','c','d','e','f','g','h'][$x].(8-$y)."\"><img src=\"pieces/".$FEN[$y][$x].".svg\" width=\"64\" height=\"64\"></div>";
                    echo "<div class=\"white\" id=\"".['a','b','c','d','e','f','g','h'][$x].(8-$y-($x%2))."\"><img src=\"pieces/".$FEN[$y+($x%2)][$x].".svg\" width=\"64\" height=\"64\"></div>";
                    if (!($x%2)) echo "<div class=\"black\" id=\"".['a','b','c','d','e','f','g','h'][$x].(8-$y-1)."\"><img src=\"pieces/".$FEN[$y+1][$x].".svg\" width=\"64\" height=\"64\"></div>";
                }
            }
        ?>
    </div>
    <form method="post">
            <input type="text" name="FEN">
            <input type="submit">
    </form>
</body>
</html>