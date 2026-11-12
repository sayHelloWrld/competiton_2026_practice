import { getPosts } from "@/lib/api";
import type { Post } from "@/lib/types/post";
import { reactive, type Reactive} from "vue";

const posts: Reactive<Post[]> = reactive(await getPosts());

export function injectPosts() {
    return posts;
}

