import React from 'react';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Create({ users }) {
    const { data, setData, post, processing, errors } = useForm({
        user_id: '',
    });

    const submit = (event) => {
        event.preventDefault();

        post('/chat');
    };

    return (
        <>
            <Head title="New Conversation" />

            <div className="min-h-screen bg-[#080b14] px-4 py-10 text-white">
                <div className="mx-auto max-w-xl">
                    <div className="mb-6">
                        <Link
                            href="/chat"
                            className="text-sm text-white/40 transition hover:text-white"
                        >
                            ← Back to conversations
                        </Link>
                    </div>

                    <div className="rounded-3xl border border-white/10 bg-white/4 p-6 shadow-2xl backdrop-blur-xl">
                        <div className="mb-8">
                            <h1 className="text-2xl font-semibold">
                                New Conversation
                            </h1>

                            <p className="mt-2 text-sm text-white/40">
                                Choose a user to start a private conversation.
                            </p>
                        </div>

                        <form onSubmit={submit}>
                            <label className="mb-2 block text-sm text-white/60">
                                Select user
                            </label>

                            <select
                                value={data.user_id}
                                onChange={(event) =>
                                    setData('user_id', event.target.value)
                                }
                                className="w-full rounded-xl border border-white/10 bg-[#111522] px-4 py-3 text-white outline-none transition focus:border-violet-500"
                            >
                                <option value="">
                                    Choose a user...
                                </option>

                                {users.map((user) => (
                                    <option key={user.id} value={user.id}>
                                        {user.name}
                                    </option>
                                ))}
                            </select>

                            {errors.user_id && (
                                <p className="mt-2 text-sm text-red-400">
                                    {errors.user_id}
                                </p>
                            )}

                            <button
                                type="submit"
                                disabled={processing || !data.user_id}
                                className="mt-6 w-full rounded-xl bg-violet-600 py-3 font-semibold transition hover:bg-violet-500 disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                {processing
                                    ? 'Creating...'
                                    : 'Start Conversation'}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </>
    );
}