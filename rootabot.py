import streamlit as st
import ollama
import uuid
from typing import List, Dict
import json
import os

# Set Page Configuration
st.set_page_config(
    page_title="RootaBot: Botanical Wisdom",
    page_icon="🌿",
    layout="wide"
)

# Custom Styling Module
class RootaBotStyles:
    @staticmethod
    def apply_custom_styling():
        st.markdown("""
        <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap');
        :root {
            --primary-color: #2C8C99;
            --secondary-color: #5CDB95;
            --accent-color: #379683;
            --background-color: #F4F9F9;
            --text-color: #05386B;
        }
        .stApp {
            background-color: var(--background-color);
            font-family: 'Inter', sans-serif;
            color: var(--text-color);
        }
        .css-1aumxhk {
            background-color: rgba(92, 219, 149, 0.1);
            border-right: 2px solid var(--secondary-color);
        }
        .stTextInput > div > div > input {
            border: 2px solid var(--primary-color);
            border-radius: 10px;
            padding: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .stTextInput > div > div > input:focus {
            box-shadow: 0 0 10px rgba(44, 140, 153, 0.3);
            border-color: var(--accent-color);
        }
        .user-message {
            background-color: rgba(44, 140, 153, 0.1);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
        }
        .assistant-message {
            background-color: rgba(92, 219, 149, 0.1);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
        }
        .stButton > button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            transition: all 0.3s ease;
        }
        .stButton > button:hover {
            background-color: var(--accent-color);
            transform: scale(1.05);
        }
        .stSpinner > div > svg {
            color: var(--primary-color);
        }
        ::-webkit-scrollbar {
            width: 10px;
        }
        ::-webkit-scrollbar-track {
            background: var(--background-color);
        }
        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 5px;
        }
        </style>
        """, unsafe_allow_html=True)

# Configuration Management
class RootaBotConfig:
    SYSTEM_PROMPT = """You are RootaBot, an expert AI assistant focused exclusively on indigenous plants and traditional medicine from Northeast India.

Guidelines:
1. Provide detailed insights only about:
   - Indigenous medicinal plants of Northeast India
   - Traditional healing practices
   - Ethnobotanical knowledge
   - Plant conservation efforts
   - Cultural significance of botanical resources

2. Response Requirements:
   - Include scientific and local names of plants
   - Specify exact regional context (state/tribal region)
   - Describe traditional preparation methods
   - Highlight medicinal properties and uses
   - Provide historical or cultural background
   - Add appropriate medical disclaimers

3. Maintain a scholarly, respectful, and informative tone
4. Prioritize accuracy and depth of indigenous knowledge"""

    KEYWORDS = [
        'medicinal', 'plant', 'herb', 'traditional', 'indigenous', 
        'northeast', 'ethnobotany', 'healing', 'remedy', 'botanical',
        'assam', 'meghalaya', 'arunachal', 'nagaland', 'manipur', 
        'mizoram', 'tripura', 'sikkim', 'tribal', 'medicine'
    ]

# Conversation Management
class ConversationManager:
    @staticmethod
    def save_conversation(messages: List[Dict]):
        """Save conversation to a file with unique identifier"""
        try:
            os.makedirs("conversations", exist_ok=True)
            filename = f"conversations/{uuid.uuid4()}.json"
            with open(filename, 'w', encoding='utf-8') as f:
                json.dump(messages, f, indent=2, ensure_ascii=False)
            return filename
        except Exception as e:
            st.error(f"Conversation saving failed: {e}")
            return None

    @staticmethod
    def is_relevant_query(query: str) -> bool:
        """Check query relevance based on keywords"""
        query_lower = query.lower()
        return any(keyword in query_lower for keyword in RootaBotConfig.KEYWORDS)

# Ollama Integration
class OllamaIntegration:
    @staticmethod
    def generate_response(query: str):
        """Generate streaming response using Ollama"""
        if not ConversationManager.is_relevant_query(query):
            yield ("I can only discuss traditional medicinal plants and practices "
                   "from Northeast India. Please rephrase your question to focus on "
                   "indigenous botanical knowledge.")
            return

        try:
            stream = ollama.chat(
                model='llama2',
                messages=[
                    {"role": "system", "content": RootaBotConfig.SYSTEM_PROMPT},
                    {"role": "user", "content": query}
                ],
                stream=True
            )
            
            full_response = ""
            for chunk in stream:
                if 'message' in chunk:
                    content = chunk['message'].get('content', '')
                    if content:
                        full_response += content
                        yield content
        except Exception as e:
            yield f"🌿 Error generating response: {str(e)}"

# Main Streamlit Application
def main():
    # Apply Custom Styling
    RootaBotStyles.apply_custom_styling()

    # App Title and Description
    st.title("🌿 RootaBot: Northeast India's Botanical Heritage")
    st.markdown("*Unveiling the rich ethnobotanical wisdom of Northeast India*")

    # Sidebar Information
    with st.sidebar:
        st.header("🔬 About RootaBot")
        st.info("""
        A specialized AI assistant dedicated to:
        - 🍃 Indigenous medicinal plants
        - 🌱 Traditional healing practices
        - 🏞️ Cultural botanical knowledge
        - 🔍 Ethnobotanical research
        """)
        
        st.header("🔗 Quick Resources")
        st.markdown("• [Northeast Council](https://www.necouncil.gov.in/)")
        st.markdown("• [Botanical Research Resources](https://www.bioone.org/)")
        
        st.header("🌈 Explore More")
        if st.button("Reset Conversation"):
            st.session_state.clear()

    # Initialize Chat History
    if "messages" not in st.session_state:
        st.session_state.messages = []

    # Display Chat History
    for msg in st.session_state.messages:
        if msg["role"] == "user":
            st.markdown(f'<div class="user-message">{msg["content"]}</div>', unsafe_allow_html=True)
        else:
            st.markdown(f'<div class="assistant-message">{msg["content"]}</div>', unsafe_allow_html=True)

    # User Input
    if prompt := st.chat_input("Ask about traditional medicine in Northeast India"):
        # User Message
        st.session_state.messages.append({"role": "user", "content": prompt})
        st.markdown(f'<div class="user-message">{prompt}</div>', unsafe_allow_html=True)

        # Assistant Response
        response_placeholder = st.empty()
        full_response = ""
        
        # Stream the response
        for response_chunk in OllamaIntegration.generate_response(prompt):
            full_response += response_chunk
            response_placeholder.markdown(f'<div class="assistant-message">{full_response}▌</div>', unsafe_allow_html=True)

        # Save the complete response
        response_placeholder.markdown(f'<div class="assistant-message">{full_response}</div>', unsafe_allow_html=True)
        st.session_state.messages.append({"role": "assistant", "content": full_response})
        

if __name__ == "__main__":
    main()
