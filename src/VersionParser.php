<?php

/*
 * This file is part of questocat/version-comparator package.
 *
 * (c) questocat <zhengchaopu@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Questocat\VersionComparator;

class VersionParser
{
    use ValidatesSemVer;

    /**
     * Parse the semantic version strings.
     *
     * @param string $versionStr
     *
     * @throws InvalidVersionException
     *
     * @return array
     */
    public static function parse($versionStr)
    {
        if (!static::validateVersion($versionStr)) {
            throw new InvalidVersionException("Invalid version string: {$versionStr}");
        }

        $buildMetadata = [];
        $preRelease = [];

        if (false !== strpos($versionStr, '+')) {
            list($versionStr, $buildMetadata) = explode('+', $versionStr);
            $buildMetadata = explode('.', $buildMetadata);
        }

        if (false !== ($pos = strpos($versionStr, '-'))) {
            $original = $versionStr;
            $versionStr = substr($versionStr, 0, $pos);
            $preRelease = explode('.', substr($original, $pos + 1));
        }

        list($major, $minor, $patch) = array_map('intval', explode('.', $versionStr));

        return compact('major', 'minor', 'patch', 'preRelease', 'buildMetadata');
    }
}
