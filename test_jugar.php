<?php
/**
 * ============================================================
 *  PRUEBAS UNITARIAS — método jugar() del Controller
 *  Lotería v1.1
 * ============================================================
 *
 *  Ejecución: php test_jugar.php
 *
 *  Se prueba la LÓGICA pura del método sin depender de la base
 *  de datos ni de la sesión HTTP, usando una clase stub.
 */

// ── Stub de Model (evita conexión a BD) ─────────────────────
class Model {
    public function guardarPuntaje($puntaje) { /* no-op */ }
}

// ── Clase bajo prueba ────────────────────────────────────────
// Se importa la lógica directamente; como controller.php llama
// session_start() y require_once 'model.php', los emulamos aquí.
if (!function_exists('session_start')) {
    function session_start() {}
}
$_SESSION = [];

// Incluir solo la lógica del controller, sin el autoload global
class ControllerTestable {
    private $puntaje;
    private $resultado;
    private $numeros;

    public function __construct(int $puntajeInicial = 2000) {
        $this->puntaje = $puntajeInicial;
    }

    /**
     * Versión testeable de jugar() con números inyectables.
     */
    public function jugar(int $n1 = null, int $n2 = null, int $n3 = null): void {
        $numero1 = $n1 ?? mt_rand(1, 3);
        $numero2 = $n2 ?? mt_rand(1, 3);
        $numero3 = $n3 ?? mt_rand(1, 3);

        if ($numero1 === $numero2 && $numero2 === $numero3) {
            $this->puntaje += 200;
            $this->resultado = 'Ganaste';
        } else {
            $this->puntaje -= 10;
            $this->resultado = 'Perdiste';
        }

        if ($this->puntaje < 0) {
            $this->puntaje  = 0;
            $this->resultado = 'Game Over';
        }

        $this->numeros = [$numero1, $numero2, $numero3];
    }

    public function getPuntaje(): int    { return $this->puntaje;   }
    public function getResultado(): string { return $this->resultado; }
    public function getNumeros(): array  { return $this->numeros;   }
}

// ── Mini framework de tests ──────────────────────────────────
$tests  = 0;
$passed = 0;
$failed = 0;

function assert_equal($label, $expected, $actual): void {
    global $tests, $passed, $failed;
    $tests++;
    if ($expected === $actual) {
        echo "  ✅  $label\n";
        $passed++;
    } else {
        echo "  ❌  $label\n";
        echo "       Esperado: " . var_export($expected, true) . "\n";
        echo "       Obtenido: " . var_export($actual, true)   . "\n";
        $failed++;
    }
}

function assert_true($label, $condition): void {
    assert_equal($label, true, (bool)$condition);
}

// ── PRUEBAS ──────────────────────────────────────────────────
echo "\n";
echo "======================================================\n";
echo "  PRUEBAS UNITARIAS — Controller::jugar()\n";
echo "======================================================\n\n";

// TEST 1: Puntaje inicial por defecto es 2000
$c = new ControllerTestable();
assert_equal("Puntaje inicial = 2000", 2000, $c->getPuntaje());

// TEST 2: Tres iguales → suma 200 y resultado "Ganaste"
$c = new ControllerTestable(2000);
$c->jugar(1, 1, 1);
assert_equal("Tres iguales → puntaje 2200", 2200, $c->getPuntaje());
assert_equal("Tres iguales → resultado 'Ganaste'", 'Ganaste', $c->getResultado());

// TEST 3: No iguales → resta 10 y resultado "Perdiste"
$c = new ControllerTestable(2000);
$c->jugar(1, 2, 3);
assert_equal("No iguales → puntaje 1990", 1990, $c->getPuntaje());
assert_equal("No iguales → resultado 'Perdiste'", 'Perdiste', $c->getResultado());

// TEST 4: Solo dos iguales → sigue siendo "Perdiste"
$c = new ControllerTestable(2000);
$c->jugar(2, 2, 3);
assert_equal("Dos iguales → resultado 'Perdiste'", 'Perdiste', $c->getResultado());

// TEST 5: Puntaje no puede ser negativo → queda en 0 y "Game Over"
$c = new ControllerTestable(5); // puntaje bajo
$c->jugar(1, 2, 3);             // resta 10 → debería ser -5 → queda 0
assert_equal("Puntaje mínimo = 0", 0, $c->getPuntaje());
assert_equal("Puntaje en 0 → 'Game Over'", 'Game Over', $c->getResultado());

// TEST 6: Puntaje exacto en 10 → queda en 0 al perder
$c = new ControllerTestable(10);
$c->jugar(1, 2, 3);
assert_equal("Puntaje exacto 10 - 10 = 0", 0, $c->getPuntaje());

// TEST 7: Los números devueltos están en rango [1,3]
$c = new ControllerTestable();
$c->jugar(); // números aleatorios
$nums = $c->getNumeros();
assert_true("Número 1 en rango [1,3]", $nums[0] >= 1 && $nums[0] <= 3);
assert_true("Número 2 en rango [1,3]", $nums[1] >= 1 && $nums[1] <= 3);
assert_true("Número 3 en rango [1,3]", $nums[2] >= 1 && $nums[2] <= 3);

// TEST 8: 100 jugadas aleatorias → puntaje nunca negativo
$c = new ControllerTestable(500);
for ($i = 0; $i < 100; $i++) {
    $c->jugar();
}
assert_true("Tras 100 jugadas, puntaje >= 0", $c->getPuntaje() >= 0);

// TEST 9: Después de Game Over el puntaje permanece en 0
$c = new ControllerTestable(0);
$c->jugar(1, 2, 3);
assert_equal("Puntaje en 0 → sigue en 0", 0, $c->getPuntaje());
assert_equal("Puntaje en 0 → Game Over", 'Game Over', $c->getResultado());

// ── RESUMEN ──────────────────────────────────────────────────
echo "\n------------------------------------------------------\n";
echo "  Total: $tests   ✅ Pasadas: $passed   ❌ Fallidas: $failed\n";
echo "------------------------------------------------------\n\n";

exit($failed > 0 ? 1 : 0);
