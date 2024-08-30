import { API_BASE_URL } from '@/env';
import currentUser from './currentUser';

export async function fetchProfile(): Promise<Object> {
  const userdata = await currentUser();

  return fetch(`${API_BASE_URL}profile/show`, {
    method: 'GET',
    headers: {
      'accept': 'application/json',
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${userdata.token}`
    },
  })
    .then(response => {
      return response.json();
    });
}

export async function updateProfile({ data }: { data: any }): Promise<Object> {
  const userdata = await currentUser();

  const formData = new FormData();
  formData.append('name', data.name);
  formData.append('phone', data.phone);
  formData.append('date_of_birth', data.date_of_birth);

  if (data.profile_picture) {
    formData.append('profile_picture', data.profile_picture);
  }

  return fetch(`${API_BASE_URL}profile/update`, {
    method: 'POST',
    headers: {
      'accept': 'application/json',
      'Authorization': `Bearer ${userdata.token}`,
    },
    body: formData,
  })
    .then(response => response.json());
}