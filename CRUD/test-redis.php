<?php

try {
    $redis = new Redis();
    $redis->connect('redis', 6379);
    echo "Connexion à Redis réussie\n";
    
    // Test d'écriture
    $redis->set('test_key', 'test_value');
    echo "Écriture réussie\n";
    
    // Test de lecture
    $value = $redis->get('test_key');
    echo "Lecture réussie: " . $value . "\n";
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
} 