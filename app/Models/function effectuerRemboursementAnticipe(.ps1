function effectuerRemboursementAnticipe($montantRemboursement, $dateRemboursement) {
    
    // Mettez à jour le montant principal restant et le solde restant pour la période de remboursement anticipé
    $query = "UPDATE amortization_schedule SET principal = principal - :montantRemboursement, remaining_balance = remaining_balance - :montantRemboursement WHERE payment_date >= :dateRemboursement";
    $params = [
        'montantRemboursement' => $montantRemboursement,
        'dateRemboursement' => $dateRemboursement
    ];
    $db->execute($query, $params);
    
    // Recalculez les montants principaux restants et les soldes restants pour les périodes suivantes
    $query = "SELECT * FROM amortization_schedule WHERE payment_date > :dateRemboursement ORDER BY payment_date";
    $params = ['dateRemboursement' => $dateRemboursement];
    $result = $db->fetchAll($query, $params);
    
    $principalRestant = $montantRemboursement;
    foreach ($result as $row) {
        $principalRestant += $row['principal'];
        $row['principal'] = 0;
        $row['remaining_balance'] -= $principalRestant;
        
        // Mettez à jour les montants principaux restants et les soldes restants pour chaque période de paiement suivante
        $query = "UPDATE amortization_schedule SET principal = 0, remaining_balance = :remainingBalance WHERE payment_number = :paymentNumber";
        $params = [
            'remainingBalance' => $row['remaining_balance'],
            'paymentNumber' => $row['payment_number']
        ];
        $db->execute($query, $params);
    }
}


// Supposez que vous ayez une fonction pour modifier le taux d'intérêt
function modifierTauxInteret($nouveauTauxInteret) {
    
    // Recalculer les montants d'intérêts pour chaque période de paiement
    $query = "SELECT * FROM amortization_schedule ORDER BY payment_date";
    $result = $db->fetchAll($query);
    
    foreach ($result as $row) {
        $nouvelInteret = ($row['remaining_balance'] * $nouveauTauxInteret) / (12 * 100); // Supposant un taux d'intérêt mensuel
        
        // Mettre à jour le montant d'intérêt pour chaque période de paiement
        $query = "UPDATE amortization_schedule SET interest = :nouvelInteret WHERE payment_number = :paymentNumber";
        $params = [
            'nouvelInteret' => $nouvelInteret,
            'paymentNumber' => $row['payment_number']
        ];
        $db->execute($query, $params);
    }
}