import type { Post } from "./types/post";

const baseUrl = 'https://jsonplaceholder.typicode.com';

async function request(url: string, method: string, body: Object | undefined = undefined, params: Record<string, string> | undefined = undefined) {
    let urlParams;

    if (params) {
        urlParams = new URLSearchParams(params).toString();
    }

    const response = await fetch(`${baseUrl}${url}${urlParams || ''}`, {
        method: method.toUpperCase(),
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json"
        },
        body: (method.toUpperCase() !== 'GET') ? JSON.stringify(body) : undefined
    });

    if(!response.ok) {
        throw new ApiError("", response.status);
    }

    return await response.json();
}

export function getPosts(): Promise<Post[]> {
    return request(`/posts`, 'GET');
}

export function createPost(title: string, body: string): Post {
    
}

export function updatePost(id: number, title: string, body: string): Post {

}

export function deletePost(id: number) {

}

export class ApiError extends Error {
    errorCode: number;

    constructor(message: string, errorCode: number) {
        super(message);
        this.name = "ApiError";
        this.errorCode = errorCode;
    }
}