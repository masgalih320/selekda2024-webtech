import { API_BASE_URL } from '@/env';
import currentUser from './currentUser';

export async function fetchBlog(): Promise<Object> {
  return fetch(`${API_BASE_URL}blog`, {
    headers: {
      'accept': 'application/json'
    }
  })
    .then(response => {
      return response.json();
    });
}

export async function getBlog(id: number): Promise<Object> {
  const userdata = await currentUser();

  return fetch(`${API_BASE_URL}blog/show/${id}`, {
    method: 'GET',
    headers: {
      'accept': 'application/json',
      'Authorization': `Bearer ${userdata.token}`
    }
  })
    .then(response => {
      return response.json();
    });
}

export async function updateBlog({ id, data }: { id: number, data: any }): Promise<Object> {
  const userdata = await currentUser();

  const formData = new FormData();
  formData.append('title', data.title);
  formData.append('description', data.description);
  formData.append('tags', data.tags);

  if (data.featured_image) {
    formData.append('featured_image', data.featured_image);
  }

  return fetch(`${API_BASE_URL}blog/update/${id}`, {
    method: 'POST',
    headers: {
      'accept': 'application/json',
      'Authorization': `Bearer ${userdata.token}`,
    },
    body: formData,
  })
    .then(response => response.json());
}

export async function storeBlog({ data }: { data: any }): Promise<Object> {
  const userdata = await currentUser();

  const formData = new FormData();
  formData.append('title', data.title);
  formData.append('description', data.description);
  formData.append('tags', data.tags);

  if (data.featured_image) {
    formData.append('featured_image', data.featured_image);
  }

  return fetch(`${API_BASE_URL}blog/store`, {
    method: 'POST',
    headers: {
      'accept': 'application/json',
      'Authorization': `Bearer ${userdata.token}`,
    },
    body: formData,
  })
    .then(response => response.json());
}

export async function deleteBlog(id: number): Promise<Object> {
  const userdata = await currentUser();

  return fetch(`${API_BASE_URL}blog/destroy/${id}`, {
    method: 'DELETE',
    headers: {
      'accept': 'application/json',
      'Authorization': `Bearer ${userdata.token}`
    }
  })
    .then(response => {
      return response.json();
    });
}