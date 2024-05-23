<?php
class VersionComparator {
    public static function isAfter(string $versionA, string $versionB): bool {
        // Split versions into build number and version number 
        $partsA = explode('+', $versionA);
        $partsB = explode('+', $versionB);
        
        // Get version numbers
        $versionNumA = $partsA[0];
        $versionNumB = $partsB[0];
        
        // Compare the version numbers
        if ($versionNumA === $versionNumB) {
            // If version numbers are equal, check the build numbers
            $buildNumA = isset($partsA[1]) ? $partsA[1] : 0;
            $buildNumB = isset($partsB[1]) ? $partsB[1] : 0;
            return intval($buildNumA) > intval($buildNumB);
        }
        
        // If version numbers are different, use string comparison
        return version_compare($versionNumA, $versionNumB) > 0;
    }
}

// Example :
$versionA = "1.0.17+59";
$versionB = "1.0.17+60";

$result = VersionComparator::isAfter($versionA, $versionB);
echo $result ? "$versionA is after $versionB" : "$versionA is not after $versionB";
