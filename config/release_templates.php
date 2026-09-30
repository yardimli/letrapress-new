<?php

return [
    'book-launch' => [
        'type' => 'release',
        'name' => 'New book announcement',
        'description' => 'Announce a forthcoming novel with its hook, publication details, and author context.',
        'subject' => '[Book title] arrives [publication date] from [publisher]',
        'content' => "FOR IMMEDIATE RELEASE\n\n[City, Date] — [Author name] announces [Book title], a [genre] novel arriving [publication date] from [publisher].\n\n[One-sentence hook that makes the book timely and distinctive.]\n\n[Two short paragraphs covering the protagonist, central conflict, themes, and intended readership.]\n\n“[A concise quote from the author about why this story matters now],” said [Author name].\n\n[Book title] will be available in [formats] through [retailers/distributor]. Review copies and interviews are available.\n\nAbout the author\n[Short author biography and notable previous work.]\n\nMedia contact\n[Name, email, website]",
    ],
    'review-copy' => [
        'type' => 'pitch',
        'name' => 'Review-copy pitch',
        'description' => 'Offer an advance or finished copy to reviewers who already cover this genre.',
        'subject' => 'Review copy: [Book title], a [specific genre/angle] novel',
        'content' => "Hello [First name],\n\nI’m reaching out because your coverage of [relevant book, theme, or article] suggests [Book title] may suit your readers.\n\n[Book title] is a [word count]-word [genre] novel about [one-sentence hook]. It will be published by [publisher] on [date].\n\nWhy it may fit your coverage:\n• [Specific theme or audience connection]\n• [Credibility, award, or unusual research]\n• [Timely cultural or news angle]\n\nI would be glad to send a [digital/print] review copy and arrange an interview with [Author name]. May I send the book?\n\nBest,\n[Name and contact details]",
    ],
    'award' => [
        'type' => 'release',
        'name' => 'Award or shortlist news',
        'description' => 'Turn a prize, shortlist, or notable selection into a concise news announcement.',
        'subject' => '[Book title] named [winner/finalist] for [award]',
        'content' => "FOR IMMEDIATE RELEASE\n\n[Book title] by [Author name] has been named [winner/finalist] for the [Award name], recognizing [what the award honors].\n\n[Brief description of the book and its central idea.]\n\n“[Author quote about the recognition and the people behind the book],” said [Author name].\n\nThe [Award name] ceremony will take place [date/location]. [Book title] is available in [formats].\n\nAbout the author\n[Biography.]\n\nMedia contact\n[Contact details]",
    ],
    'event' => [
        'type' => 'release',
        'name' => 'Reading, festival, or tour',
        'description' => 'Promote an author appearance with useful event facts and a clear local angle.',
        'subject' => '[Author name] brings [Book title] to [venue/city] on [date]',
        'content' => "[City, Date] — [Author name], author of [Book title], will appear at [venue/event] on [date and time] for [reading/conversation/signing].\n\n[Two sentences explaining the book and why the event is relevant to the local audience.]\n\nEvent details\n• Date and time: [details]\n• Venue: [name and address]\n• Tickets: [price/link or free admission]\n• Accessibility: [details]\n\nInterview opportunities and event images are available on request.\n\nMedia contact\n[Contact details]",
    ],
    'paperback' => [
        'type' => 'release',
        'name' => 'Paperback or special edition',
        'description' => 'Introduce a new edition, cover, foreword, reading guide, or bonus material.',
        'subject' => 'New [edition] of [Book title] adds [new material]',
        'content' => "FOR IMMEDIATE RELEASE\n\nA new [paperback/anniversary/illustrated] edition of [Book title] by [Author name] will be published [date].\n\nThe edition includes [new essay, cover, illustrations, discussion guide, bonus story, or other material].\n\n[Short description of the book, its reception, and the audience for the new edition.]\n\n“[Author or publisher quote],” said [name and role].\n\nReview copies, images, and interviews are available.\n\nMedia contact\n[Contact details]",
    ],
    'rights-adaptation' => [
        'type' => 'release',
        'name' => 'Rights or adaptation announcement',
        'description' => 'Announce film, television, audio, translation, or international rights news.',
        'subject' => '[Rights buyer] acquires [rights type] to [Book title]',
        'content' => "FOR IMMEDIATE RELEASE\n\n[Company/publisher] has acquired [film/television/audio/translation] rights to [Book title] by [Author name].\n\n[Name and role] will [produce/direct/translate/publish] the project. [Relevant deal details that may be disclosed.]\n\n[One paragraph describing the book and its audience.]\n\n“[Quote from author, agent, publisher, or producer],” said [name].\n\nAbout the author\n[Biography.]\n\nMedia contact\n[Contact details]",
    ],
    'expert-commentary' => [
        'type' => 'pitch',
        'name' => 'Author expert commentary',
        'description' => 'Pitch an author’s research or lived expertise for interviews and timely commentary.',
        'subject' => 'Source for your coverage of [topic]: [Author name], author of [Book title]',
        'content' => "Hello [First name],\n\nAs you cover [topic], I wanted to offer [Author name], author of [Book title], as a source on [specific timely question].\n\n[Author surname] can speak clearly about:\n• [Specific angle]\n• [Specific angle]\n• [Specific angle]\n\nTheir perspective comes from [research, profession, reporting, or lived experience]. [One sentence connecting the book to the current story.]\n\nWould a short conversation or written comment be useful?\n\nBest,\n[Name and contact details]",
    ],
    'blank' => [
        'type' => 'release',
        'name' => 'Start with a blank page',
        'description' => 'Use your own structure while keeping the outreach and newsroom tools available.',
        'subject' => '',
        'content' => '',
    ],
];
