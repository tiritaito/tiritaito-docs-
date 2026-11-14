<?php
/**
 * TT Salmo del día — Verificación de episodios (solo lectura, no cambia nada)
 * Uso (Site Shell de Local, en la carpeta del sitio):
 *   wp eval-file /ruta/a/tt-salmo-verificar-episodios.php
 * Si no puedes dejar el archivo en esa ruta, ábrelo en el editor de texto,
 * y pégalo en un archivo nuevo dentro de la carpeta del sitio.
 *
 * Comprueba, para los episodios cuyo título empieza por «Salmo N»:
 *  - cuántos hay, y cuáles de 1 a 150 faltan
 *  - cuáles están repetidos
 *  - cuáles no tienen audio (ep_audio_url vacío)
 *  - cuáles llevan algo más que «Salmo N» en el título
 */
global $wpdb;

$filas = $wpdb->get_results(
	"SELECT ID, post_title, post_status FROM {$wpdb->posts}
	 WHERE post_type = 'episodio' AND post_title REGEXP '^Salmo[[:space:]]+[0-9]+'
	 ORDER BY ID ASC"
);

$por_num   = array();
$titulo_raro = array();
$sin_audio = array();
$no_publicados = array();

foreach ( $filas as $f ) {
	if ( ! preg_match( '/^Salmo\s+(\d+)/u', $f->post_title, $m ) ) {
		continue;
	}
	$n = (int) $m[1];
	if ( 'publish' !== $f->post_status ) {
		$no_publicados[] = $n . ' (' . $f->post_status . ')';
		continue;
	}
	$por_num[ $n ][] = (int) $f->ID;
	if ( ! preg_match( '/^Salmo\s+\d+\s*$/u', $f->post_title ) ) {
		$titulo_raro[] = $n . ': «' . $f->post_title . '»';
	}
	$audio = get_post_meta( $f->ID, 'ep_audio_url', true );
	if ( ! is_string( $audio ) || ! filter_var( $audio, FILTER_VALIDATE_URL ) ) {
		$sin_audio[] = $n;
	}
}

$faltan = array();
for ( $i = 1; $i <= 150; $i++ ) {
	if ( empty( $por_num[ $i ] ) ) {
		$faltan[] = $i;
	}
}
$repetidos = array();
foreach ( $por_num as $n => $ids ) {
	if ( count( $ids ) > 1 ) {
		$repetidos[] = $n . ' (IDs ' . implode( ', ', $ids ) . ')';
	}
}
$fuera_rango = array_filter( array_keys( $por_num ), function ( $n ) {
	return $n < 1 || $n > 150;
} );

echo "Episodios publicados con título «Salmo N»: " . count( $por_num ) . " números distintos\n";
echo "Faltan de 1 a 150: " . ( $faltan ? implode( ', ', $faltan ) : 'ninguno' ) . "\n";
echo "Repetidos: " . ( $repetidos ? implode( '; ', $repetidos ) : 'ninguno' ) . "\n";
echo "Sin audio válido: " . ( $sin_audio ? implode( ', ', $sin_audio ) : 'ninguno' ) . "\n";
echo "Título con algo más que «Salmo N»: " . ( $titulo_raro ? implode( ' | ', $titulo_raro ) : 'ninguno' ) . "\n";
echo "No publicados (borrador, papelera…): " . ( $no_publicados ? implode( ', ', $no_publicados ) : 'ninguno' ) . "\n";
echo "Números fuera de 1-150: " . ( $fuera_rango ? implode( ', ', $fuera_rango ) : 'ninguno' ) . "\n";
echo "\n";
