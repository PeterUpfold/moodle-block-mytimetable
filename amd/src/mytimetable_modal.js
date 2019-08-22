// vim: set tabstop=8 softtabstop=0 expandtab shiftwidth=4 smarttab
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Load the site admin nav tree via ajax and render the response.
 *
 * @module     block_mytimetable/mytimetable_modal
 * @copyright  2019 Test Valley School
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['jquery', 'core/modal_factory', 'core/ajax'], function($, ModalFactory) {
    
    return {
        init: function(timetableUrl, user) {
            ModalFactory.create({
                title: 'Timetable',
                /* we will call the modal's setBody() later */
            }, $('#view-timetable-button')).done(function(modal) {
                // lazy load the content
                $('#view-timetable-button').click(function() {
                    $.when(
                        $.ajax(timetableUrl + '?username=' + encodeURIComponent(user))
                    ).then(
                        function(content, textStatus, jqXHR) {
                            modal.setBody(content);
                        },
                        function(content, textStatus, jqXHR) {
                            // failed
                            console.log('Failed to load timetable with status code ' + textStatus);
                        }
                    );
                });
            });
        }
    };
});
