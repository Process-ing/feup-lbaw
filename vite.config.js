import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
	plugins: [
		laravel({
			input: [
				"resources/css/app.css",
				"resources/js/app.js",
				"resources/js/header.js",
				"resources/js/user.js",
				"resources/js/admin.js",
				"resources/js/faq.js",
				"resources/js/group.js",
				"resources/js/manage-group-requests.js",
				"resources/js/manage-group-invites.js",
				"resources/js/manage-group-members.js",
				"resources/js/user-groups.js",
				"resources/js/user-invites.js",
				"resources/js/notifications.js",
				"resources/js/auth.js",
				"resources/js/home.js",
				"resources/js/post.js",
				"resources/js/search.js",
				"resources/js/comment.js",
			],
			refresh: true,
		}),
	],
});
