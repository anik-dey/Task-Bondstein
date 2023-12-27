<?php

function findTwoIndices($target, $numbers)
{
	$left = 0;
	$right = count($numbers) - 1;

	while ($left < $right) {
		$sum = $numbers[$left] + $numbers[$right];

		if ($sum == $target) {
			return [$left, $right];
		} elseif ($sum < $target) {
			$left++;
		} else {
			$right--;
		}
	}

// No such indices found
	return null;
}

// Example usage:
$targetNumber = 19;
$sortedNumbers = [2, 4, 6, 8, 11, 15, 23];

$result = findTwoIndices($targetNumber, $sortedNumbers);

if ($result !== null) {
	list($index1, $index2) = $result;
	echo "Indices with values {$sortedNumbers[$index1]} and {$sortedNumbers[$index2]} add up to $targetNumber.\n";
} else {
	echo "No such indices found.\n";
}

?>
