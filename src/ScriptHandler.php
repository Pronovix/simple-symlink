<?php

declare(strict_types=1);

/**
 * Copyright (C) 2019 PRONOVIX GROUP BVBA.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *  *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *  *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301,
 * USA.
 */

namespace Pronovix\SimpleSymlink;

use Composer\Script\Event;
use Composer\Util\Filesystem;
use Pronovix\ComposerLogger\Logger;

final class ScriptHandler
{
    /**
     * Symlinks files and directories.
     *
     * @param \Composer\Script\Event $event
     */
    public static function createSymlinks(Event $event): void
    {
        $package = $event->getComposer()->getPackage();
        $logger = new Logger('Simple Symlink', $event->getIO());
        $symlinks = (array) ($package->getExtra()['simple-symlinks'] ?? []);
        $filesystem = new Filesystem();
        foreach ($symlinks as $link => $target) {
            if (file_exists($target)) {
                if (!is_link($target)) {
                    $logger->warning('{target} already exists and it is not a symlink.', ['target' => $target]);
                }
                continue;
            }

            $target = $filesystem->normalizePath($filesystem->isAbsolutePath($target) ? $target : getcwd() . '/' . $target);
            $filesystem->ensureDirectoryExists(dirname($target));
            $link = $filesystem->normalizePath($filesystem->isAbsolutePath($link) ? $link : getcwd() . '/' . $link);
            $result = $filesystem->relativeSymlink($link, $target);
            if ($result) {
                $logger->info('Symlinking {link} to {target}.', ['target' => $target, 'link' => $link]);
            } else {
                $logger->error('Failed to symlink {link} to {target}.', ['target' => $target, 'link' => $link]);
            }
        }
    }
}
