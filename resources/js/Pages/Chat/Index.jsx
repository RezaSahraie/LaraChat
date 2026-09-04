import React from 'react';
import { Link } from '@inertiajs/react';

export default function Index({
    conversations,
    selectedConversation,
}) {
    const messages = selectedConversation?.messages ?? [];

    return (
        <div className="min-h-screen bg-[#080b14] text-white">
            <div className="flex h-screen">

                {/* Main Sidebar */}
                <aside className="w-20 border-r border-white/10 bg-white/[0.03]">
                    <div className="flex h-full flex-col items-center gap-6 py-6">

                        <div className="flex h-11 w-11 items-center justify-center rounded-2xl bg-violet-600 font-bold">
                            LC
                        </div>

                        <button className="rounded-xl bg-white/10 p-3">
                            💬
                        </button>

                        <button className="p-3 text-white/50">
                            👥
                        </button>

                        <button className="p-3 text-white/50">
                            🔔
                        </button>

                        <button className="mt-auto p-3 text-white/50">
                            ⚙️
                        </button>

                    </div>
                </aside>

                {/* Conversations */}
                <aside className="w-80 border-r border-white/10 bg-white/[0.02]">

                    <div className="border-b border-white/10 p-5">

                        <div className="mb-5 flex items-center justify-between">

                            <h1 className="text-xl font-semibold">
                                Conversations
                            </h1>

                            <button className="rounded-xl bg-violet-600 px-3 py-2">
                                +
                            </button>

                        </div>

                        <input
                            type="text"
                            placeholder="Search conversations..."
                            className="w-full rounded-xl border border-white/10 bg-white/[0.04] px-4 py-3 outline-none placeholder:text-white/30"
                        />

                    </div>

                    <div className="p-3">

                        {conversations.map((conversation) => (

                            <Link
                                key={conversation.id}
                                href={`/chat/${conversation.id}`}
                                className={`mb-1 flex w-full items-center gap-3 rounded-xl p-3 text-left transition ${
                                    selectedConversation?.id === conversation.id
                                        ? 'bg-violet-600/20'
                                        : 'hover:bg-white/[0.05]'
                                }`}
                            >

                                <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-violet-600/30">
                                    {conversation.name?.charAt(0) ?? 'C'}
                                </div>

                                <div className="min-w-0">

                                    <div className="font-medium">
                                        {conversation.name ?? 'Conversation'}
                                    </div>

                                    <div className="truncate text-sm text-white/40">
                                        {conversation.messages?.[0]?.content ??
                                            'No messages yet'}
                                    </div>

                                </div>

                            </Link>

                        ))}

                    </div>

                </aside>

                {/* Chat */}
                <main className="flex flex-1 flex-col">

                    {/* Header */}
                    <header className="flex h-20 items-center border-b border-white/10 px-6">

                        {selectedConversation ? (
                            <div>
                                <h2 className="font-semibold">
                                    {selectedConversation.name ?? 'Conversation'}
                                </h2>

                                <p className="text-sm text-white/40">
                                    {selectedConversation.users?.length ?? 0} members
                                </p>
                            </div>
                        ) : (
                            <div>
                                <h2 className="font-semibold">
                                    Select a conversation
                                </h2>

                                <p className="text-sm text-white/40">
                                    Start chatting
                                </p>
                            </div>
                        )}

                    </header>

                    {/* Messages */}
                    <div className="flex-1 overflow-y-auto p-6">

                        {!selectedConversation ? (

                            <div className="flex h-full items-center justify-center text-white/30">
                                Select a conversation to start messaging
                            </div>

                        ) : messages.length === 0 ? (

                            <div className="flex h-full items-center justify-center text-white/30">
                                No messages yet
                            </div>

                        ) : (

                            <div className="space-y-4">

                                {messages.map((message) => (

                                    <div
                                        key={message.id}
                                        className="flex items-start gap-3"
                                    >

                                        <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-violet-600/30">
                                            {message.user?.name?.charAt(0) ?? 'U'}
                                        </div>

                                        <div className="max-w-xl">

                                            <div className="mb-1 flex items-center gap-2">

                                                <span className="text-sm font-medium">
                                                    {message.user?.name ?? 'User'}
                                                </span>

                                                <span className="text-xs text-white/30">
                                                    {new Date(
                                                        message.created_at
                                                    ).toLocaleTimeString([], {
                                                        hour: '2-digit',
                                                        minute: '2-digit',
                                                    })}
                                                </span>

                                            </div>

                                            <div className="rounded-2xl rounded-tl-md bg-white/[0.06] px-4 py-3">
                                                {message.content}
                                            </div>

                                        </div>

                                    </div>

                                ))}

                            </div>

                        )}

                    </div>

                    {/* Composer */}
                    {selectedConversation && (
                        <div className="border-t border-white/10 p-4">

                            <div className="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/[0.04] p-2">

                                <button className="p-3 text-white/50">
                                    📎
                                </button>

                                <input
                                    type="text"
                                    placeholder="Type a message..."
                                    className="flex-1 bg-transparent px-2 py-3 outline-none placeholder:text-white/30"
                                />

                                <button className="rounded-xl bg-violet-600 px-5 py-3">
                                    ➤
                                </button>

                            </div>

                        </div>
                    )}

                </main>

            </div>
        </div>
    );
}